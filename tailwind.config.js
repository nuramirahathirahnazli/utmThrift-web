// tailwind.config.js
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: '#002366',    // UTM Navy Blue
                secondary: '#FFD700', // UTM Gold
            }
        },
    },
    plugins: [],
}
