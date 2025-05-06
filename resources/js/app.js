import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import grapesjs from 'grapesjs';
import 'grapesjs/dist/css/grapes.min.css';
import grapesjsPresetNewsletter from 'grapesjs-preset-newsletter';

// Common GrapesJS configuration
const grapesJsConfigBase = {
    height: '70vh',
    width: 'auto',
    storageManager: false, 
    plugins: [grapesjsPresetNewsletter],
    pluginsOpts: {
      [grapesjsPresetNewsletter]: { /* options */ }
    },
    // Asset Manager configuration
    assetManager: {
        // The `upload` endpoint configuration
        upload: '/media/upload', // Your Laravel upload route
        uploadName: 'files', // The name of the file input field in the request (Laravel expects files[])
        // GrapesJS by default sends 'files[]', but our backend expects 'files' as an array.
        // We can use `params` to ensure correct naming if needed, or adjust backend.
        // For now, assuming 'files' will work as backend expects an array for `request->file('files')`.
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        assets: '/media/assets', // Endpoint to load existing assets (Uncommented and set)
        autoAdd: 1, // Add uploaded files to the Asset Manager automatically
    },
};


document.addEventListener('DOMContentLoaded', () => {
  // Initialization for the general page builder (if #gjs exists)
  const gjsElement = document.getElementById('gjs');
  if (gjsElement) {
    grapesjs.init({
      ...grapesJsConfigBase,
      container: '#gjs',
      fromElement: true, 
    });
  }

  // Initialization for the blog edit form (if #gjs-editor-instance exists)
  const gjsEditorInstance = document.getElementById('gjs-editor-instance');
  const blogContentTextarea = document.getElementById('blog-content-textarea');

  if (gjsEditorInstance && blogContentTextarea) {
    let initialHtml = '';
    let initialCss = ''; // To store CSS if present
    let initialComponents = null;
    let initialStyles = null;

    try {
        const rawContent = blogContentTextarea.value;
        if (rawContent) {
            const parsedContent = JSON.parse(rawContent);
            if (parsedContent) {
                initialHtml = parsedContent.html || '';
                initialCss = parsedContent.css || '';
                initialComponents = parsedContent.components || null;
                initialStyles = parsedContent.styles || null;
            }
        } else {
             initialHtml = '<p>Start your content here...</p>'; // Default for empty content
        }
    } catch (e) {
        // If parsing fails, it might be plain HTML (legacy or error) or just empty
        initialHtml = blogContentTextarea.value || '<p>Error loading content. Start fresh.</p>';
        console.warn('Could not parse blog content as JSON, attempting to load as HTML or default. Original error:', e);
    }

    const editor = grapesjs.init({
        ...grapesJsConfigBase, // Spread common config, now includes assetManager
        container: '#gjs-editor-instance',
        // Load components and styles separately for better control if they exist
        // components: initialHtml, // If only HTML is needed directly
        // css: initialCss,      // If only CSS is needed directly
    });

    // Set content after initialization using GrapesJS API for complex data
    if (initialComponents) {
        editor.setComponents(initialComponents);
        if (initialStyles) {
             editor.setStyle(initialStyles); // Apply styles if they exist
        }
    } else if (initialHtml) {
        editor.setComponents(initialHtml); // Fallback to HTML if no components structure
        // If you stored CSS separately and not in initialStyles, apply it here
        // if(initialCss) editor.setStyle(initialCss); 
    }
    if (initialCss && !initialStyles) { // If CSS exists but not as part of styles object, set it
        editor.addStyle(initialCss);
    }


    // Update the hidden textarea when GrapesJS content changes
    editor.on('change:changesCount', () => {
        blogContentTextarea.value = JSON.stringify({
            html: editor.getHtml(),
            css: editor.getCss(),
            components: editor.getComponents(),
            styles: editor.getStyle()
        });
    });

    // Ensure content is updated before form submission
    const blogForm = gjsEditorInstance.closest('form');
    if (blogForm) {
        blogForm.addEventListener('submit', () => {
            blogContentTextarea.value = JSON.stringify({
                html: editor.getHtml(),
                css: editor.getCss(),
                components: editor.getComponents(),
                styles: editor.getStyle()
            });
        });
    }
  }
});
