# Blacksmith Hero Block WordPress Plugin

**Author:** Andre Ranulfo  
**Tags:** hero, block, gutenberg, acf, video background, image background  
**Requires at least:** WordPress 5.0  
**Tested up to:** WordPress 6.4  
**Requires PHP:** 7.4  
**License:** GPL v2 or later  
**Version:** 1.0.0

A powerful WordPress Gutenberg block for creating stunning hero sections with video backgrounds, image backgrounds, and customizable content.

## Description

The Blacksmith Hero Block is a comprehensive WordPress plugin that adds a custom Gutenberg block for creating beautiful hero sections on your website. It supports multiple background types including solid colors, images, and YouTube video backgrounds.

### Features

✅ **Multiple Background Types**
- Solid color backgrounds
- Image backgrounds with full customization
- YouTube video backgrounds with autoplay, mute, and loop controls

✅ **Customizable Content**
- Hero title and subtitle
- Call-to-action button with custom colors and links
- Responsive design for all devices

✅ **Advanced Video Features**
- YouTube video background integration
- Autoplay, mute, and loop controls
- Default fallback video when no URL is provided
- Mobile-optimized video playback

✅ **Developer Friendly**
- Clean, semantic HTML structure
- CSS custom properties for easy styling
- Build process with Gulp and SCSS
- ACF integration for field management

## Installation

### Prerequisites
- **Advanced Custom Fields (ACF) Pro** - Required for the block to function
- WordPress 5.0 or higher
- PHP 7.4 or higher

### Installation Steps

1. **Install ACF Pro**
   - Download ACF Pro from [advancedcustomfields.com](https://www.advancedcustomfields.com/pro/)
   - Install and activate the plugin

2. **Install Blacksmith Hero Block**
   - Download the plugin archive
   - Extract to `/wp-content/plugins/blacksmith-hero-block/`
   - Activate the plugin in WordPress admin

3. **Start Using**
   - Edit any page or post
   - Add the "Blacksmith Hero" block
   - Configure your hero section

## Usage

### Basic Setup
1. Add the Blacksmith Hero block to your page
2. Choose background type (None, Image, or Video)
3. Add your title and subtitle text
4. Configure call-to-action button
5. Publish and view your hero section

### Background Types

#### Solid Color Background
- Select "None" as background type
- Choose background color
- Perfect for simple, clean designs

#### Image Background
- Select "Image" as background type
- Upload your hero image
- Automatic responsive optimization

#### Video Background
- Select "Video" as background type
- Add YouTube video URL (optional - defaults to demo video)
- Configure autoplay, mute, and loop settings
- Note: Videos are muted by default for autoplay compatibility

### Customization

The plugin generates semantic CSS classes for easy customization:

```css
.blacksmith-hero-wrapper { /* Main container */ }
.blacksmith-hero__content { /* Content wrapper */ }
.blacksmith-hero__title { /* Hero title */ }
.blacksmith-hero__subtitle { /* Hero subtitle */ }
.blacksmith-hero__cta-link { /* CTA button */ }
```

## Technical Details

### File Structure
```
blacksmith-hero-block/
├── blacksmith-hero-block.php  # Main plugin file
├── includes/                  # PHP classes and functionality
├── templates/                 # Block template files
├── assets/                    # Compiled CSS and JS
└── INSTALLATION.md           # Detailed installation guide
```

### Browser Support
- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+

### Video Background Notes
- Uses YouTube Embed API for optimal performance
- Autoplay requires muted videos (browser restriction)
- Mobile browsers may not autoplay videos
- Default fallback video provided

## Frequently Asked Questions

### Q: Do I need ACF Pro for this plugin to work?
A: Yes, ACF Pro is required for the custom fields functionality.

### Q: Can I use videos other than YouTube?
A: Currently only YouTube videos are supported for background videos.

### Q: Why isn't my video autoplaying?
A: Modern browsers require videos to be muted for autoplay. Ensure the "Video Mute" option is enabled.

### Q: Can I customize the styling?
A: Yes! The plugin uses semantic CSS classes that you can override in your theme.

## Changelog

### Version 1.0.0
- Initial release
- Support for multiple background types
- YouTube video background integration
- Responsive design
- ACF Pro integration
- Default fallback video

## Support

For support and bug reports:
1. Check the troubleshooting section in INSTALLATION.md
2. Verify ACF Pro is installed and activated
3. Test with a default WordPress theme
4. Check browser console for errors

## License

This plugin is licensed under GPL v2 or later.

## Credits

Developed with ❤️ for the WordPress community.
