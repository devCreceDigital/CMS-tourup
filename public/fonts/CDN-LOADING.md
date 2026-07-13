# Font Awesome — CDN

Font Awesome 6 Free is loaded via CDN in the layout files:

```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
```

If you need local assets, download from https://fontawesome.com/download and place:
- `public/fonts/webfonts/` — the font files
- `public/fonts/css/all.min.css` — the CSS file pointing to relative `../webfonts/` paths
