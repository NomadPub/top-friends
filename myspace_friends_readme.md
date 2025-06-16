# MySpace Top Friends WordPress Plugin

A nostalgic WordPress plugin that recreates the classic MySpace "Top Friends" display with modern WordPress functionality.

## Features

- **Configurable Owner Name**: Customize who "has plenty of Friends" 
- **WordPress Media Library Integration**: Use your existing media library for friend photos
- **Random Online Status**: Randomly displays "Online Now!" below friends based on configurable probability
- **Authentic MySpace Styling**: Matches the classic orange header and grid layout
- **Responsive Design**: Works on desktop and mobile devices
- **Easy Management**: Custom post type for managing friends
- **Shortcode Support**: Display anywhere with `[myspace_friends]`

## Installation

1. Create a new folder called `myspace-top-friends` in your `/wp-content/plugins/` directory
2. Save the main plugin file as `myspace-top-friends.php`
3. Create an `assets` folder inside the plugin directory
4. Save the CSS file as `assets/style.css`
5. Activate the plugin through the WordPress admin panel

## File Structure

```
myspace-top-friends/
├── myspace-top-friends.php (main plugin file)
├── assets/
│   ├── style.css
│   └── online-icon.png (optional - uses CSS fallback)
└── README.md
```

## Usage

### Adding Friends

1. Go to **Friends** in your WordPress admin menu
2. Click **Add New Friend**
3. Enter the friend's name as the title
4. Set a **Featured Image** (this will be their photo)
5. Publish the friend

### Configuring Settings

1. Go to **Settings > MySpace Friends**
2. Configure:
   - **Owner Name**: The name that appears in "Name has plenty of Friends"
   - **Friends Count Text**: What appears after "has" (e.g., "plenty of", "405", "tons of")
   - **Number of Friends to Show**: How many friends display at once (1-20)
   - **Online Status Probability**: Percentage chance friends show as "Online Now!"

### Displaying Friends

Use the shortcode `[myspace_friends]` in any post, page, or text widget.

**Optional Parameters:**
- `count`: Override the number of friends to show
  - Example: `[myspace_friends count="6"]`

## Customization

### Styling

The plugin uses authentic MySpace styling with:
- **Orange gradient header** (`#FF6600` to `#FF4400`)
- **Classic blue links** (`#0066CC`)
- **Grid layout** with responsive breakpoints
- **Retro border effects** and spacing

### Friend Order

Friends display in **menu order** (ascending). You can set custom order values when editing friends, or they'll display in the order they were created.

### Online Status

The **"Online Now!"** status appears randomly based on your configured probability percentage. Each page load generates new random statuses for an authentic MySpace feel.

## Technical Notes

- Creates a custom post type `myspace_friend`
- Stores settings in `myspace_friends_settings` option
- CSS is enqueued only when shortcode is used
- Fully compatible with WordPress media library
- No external dependencies required

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Internet Explorer 11+
- Mobile responsive design

## Troubleshooting

**Friends not showing photos?**
- Ensure each friend has a Featured Image set
- Check that images exist in your media library

**Styling looks off?**
- Clear any caching plugins
- Check for CSS conflicts with your theme
- Ensure the CSS file is properly uploaded

**Shortcode not working?**
- Verify the plugin is activated
- Check that you're using `[myspace_friends]` exactly
- Ensure you have published friends

## Version History

- **1.0.0**: Initial release with core functionality

## License

GPL v2 or later