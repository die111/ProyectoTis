const fs = require('fs');
const path = require('path');

(async () => {
  try {
    // Import Tailwind v4 node API
    const tailwind = require('@tailwindcss/node');
    
    // Read input CSS
    const inputCss = fs.readFileSync(path.resolve(__dirname, '..', 'resources/css/app.css'), 'utf8');
    
    // Read config
    const config = require(path.resolve(__dirname, '..', 'tailwind.config.cjs'));
    
    console.log('Config loaded:', JSON.stringify(config, null, 2));
    console.log('Input CSS length:', inputCss.length);
    console.log('Content paths:', config.content);
    
    // Collect content files
    function collectFiles(dir, exts) {
      const results = [];
      if (!fs.existsSync(dir)) return results;
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
    
    const contentFiles = collectFiles(path.resolve(__dirname, '..', 'resources'), ['.blade.php', '.js', '.vue']);
    console.log(`Found ${contentFiles.length} content files`);
    console.log('Sample files:', contentFiles.slice(0, 5));
    
    // Build with Tailwind v4 API
    // The compile API expects CSS as first param, config path as second
    const result = await tailwind.compile(inputCss, {
      loadConfig: path.resolve(__dirname, '..', 'tailwind.config.cjs')
    });
    
    console.log('Build result CSS length:', result.css ? result.css.length : 0);
    
    if (result.css) {
      const outPath = path.resolve(__dirname, '..', 'public/build/assets/app-tailwind-v4.css');
      fs.mkdirSync(path.dirname(outPath), { recursive: true });
      fs.writeFileSync(outPath, result.css, 'utf8');
      console.log('✓ Wrote', outPath);
      console.log('CSS preview (first 500 chars):', result.css.substring(0, 500));
    } else {
      console.error('✗ No CSS generated');
    }
    
  } catch (e) {
    console.error('Error:', e);
    process.exit(1);
  }
})();
