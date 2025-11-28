const fs = require('fs');
const postcss = require('postcss');
const tailwindPostcss = require('@tailwindcss/postcss');
const path = require('path');
const autoprefixer = require('autoprefixer');

(async () => {
  try {
    const css = fs.readFileSync('resources/css/app.css', 'utf8');
    const tailwindConfigPath = path.resolve(__dirname, '..', 'tailwind.config.cjs');
    // Resolve content files explicitly to avoid glob resolution differences on Windows
    // Collect content files by walking resources directory (no extra deps)
    function collectFiles(dir, exts) {
      const results = [];
      const entries = fs.readdirSync(dir, { withFileTypes: true });
      for (const entry of entries) {
        const full = path.join(dir, entry.name);
        if (entry.isDirectory()) {
          results.push(...collectFiles(full, exts));
        } else if (exts.some(e => full.endsWith(e))) {
          results.push(full);
        }
      }
      return results;
    }
    const contentFiles = collectFiles(path.resolve(__dirname, '..', 'resources'), ['.blade.php', '.js', '.css', '.vue']);
    const result = await postcss([tailwindPostcss({ config: tailwindConfigPath, content: contentFiles }), autoprefixer()]).process(css, {
      from: 'resources/css/app.css'
    });
    fs.mkdirSync('public/build/assets', { recursive: true });
    fs.writeFileSync('public/build/assets/app-postcss.css', result.css, 'utf8');
    console.log('Wrote public/build/assets/app-postcss.css (length:', result.css.length, ')');
  } catch (e) {
    console.error(e);
    process.exit(1);
  }
})();
