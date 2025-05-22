<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\JsonResponse;

class SellerItemController extends Controller
{
    public function show($id): JsonResponse
    {
         \Log::info("Fetching item with ID: $id");
        // Retrieve the item along with its category
         $item = Item::with('category')->find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found or invalid ID.',
            ], 404);
        }

        $itemArray = $item->toArray();
        $itemArray['images'] = json_decode($item->image);

        return response()->json([
            'success' => true,
            'item' => $itemArray,
        ], 200);

    }

    public function getCategories()
    {
        \Log::info('Categories endpoint hit');
        $categories = ItemCategory::select('id', 'name')->get();
    
        return response()->json([
            'success' => true,
            'itemcategories' => $categories
        ]);

    }
    
    public function store(Request $request)
    {
       $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:itemcategories,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'condition' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg',
           
        ]);
                        
        // Extract image URLs and convert to full URLs
        //$images = $request->images;
        $imageUrls = [];

        foreach ($request->file('images') as $image) {
            $uploadedFileUrl = Cloudinary::upload($image->getRealPath())->getSecurePath();
            $imageUrls[] = $uploadedFileUrl;
        }

        // Prepare item data
        $itemData = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'image' => json_encode($imageUrls),
            'quantity' => 1,
            'status' => 'Available', 
            'user_id' => auth()->id() ?? 7,
        ];

        $item = Item::create($itemData);

        // Reload the item with relationships
        $itemWithRelations = Item::with('category')->find($item->id);

        return response()->json([
            'success' => true,
            'message' => 'Item added successfully.',
            'item' => [
                'id' => $itemWithRelations->id,
                'name' => $itemWithRelations->name,
                'description' => $itemWithRelations->description,
                'price' => $itemWithRelations->price,
                'condition' => $itemWithRelations->condition,
                'images' => json_decode($itemWithRelations->image), 
                'category' => $itemWithRelations->category->name, 
                'user_id' => $itemWithRelations->user_id,
            ]
        ], 201);
    }

    public function myItems(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $items = Item::with('category')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json([
            'success' => true,
            'items' => $items,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        // Validate input (you can improve this if needed)
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:itemcategories,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'condition' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg',
        ]);

        // Update text fields
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->condition = $request->condition;
        $item->category_id = $request->category_id;

        // Optional: Replace images if new ones are uploaded
        if ($request->hasFile('images')) {
            $imageUrls = [];
            foreach ($request->file('images') as $image) {
                $uploadedFileUrl = Cloudinary::upload($image->getRealPath())->getSecurePath();
                $imageUrls[] = $uploadedFileUrl;
            }

            $item->image = json_encode($imageUrls); // Save as JSON string
        }

        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Item updated successfully.',
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'price' => $item->price,
                'condition' => $item->condition,
                'images' => json_decode($item->image),
                'category_id' => $item->category_id,
            ]
        ], 200);
    }


    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        // Delete the item
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully.',
        ], 200);
    }

}
