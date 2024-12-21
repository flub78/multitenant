# PHP Web Application Attachment System Implementation Guide

## Overview
This guide provides a complete solution for implementing an attachment system in a PHP web application with the following features:
- Image thumbnails with full-size preview
- Browser-supported file preview
- File downloads for unsupported types
- Responsive design
- Security measures

## Dependencies

### CSS Framework
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
```

### Image Preview Library
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplelightbox/2.10.3/simple-lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/simplelightbox/2.10.3/simple-lightbox.min.js"></script>
```

## Implementation

### 1. PHP Class Implementation
Create a new file `AttachmentHandler.php`:

```php
<?php
class AttachmentHandler {
    private $supportedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private $browserSupportedTypes = [
        'application/pdf',
        'text/plain',
        'text/html',
        'audio/mpeg',
        'video/mp4',
        'image/svg+xml'
    ];
    
    private $thumbnailWidth = 150;
    private $thumbnailHeight = 150;

    /**
     * Generate thumbnail for image files
     */
    public function createImageThumbnail($sourcePath, $destinationPath) {
        list($width, $height) = getimagesize($sourcePath);
        $ratio = min($this->thumbnailWidth / $width, $this->thumbnailHeight / $height);
        $newWidth = $width * $ratio;
        $newHeight = $height * $ratio;

        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        
        $source = imagecreatefromstring(file_get_contents($sourcePath));
        
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        imagejpeg($thumb, $destinationPath, 80);
        imagedestroy($thumb);
        imagedestroy($source);
        
        return true;
    }

    /**
     * Get HTML for displaying the attachment
     */
    public function getAttachmentHtml($fileInfo) {
        $mimeType = $fileInfo['mime_type'];
        $filePath = $fileInfo['file_path'];
        $fileName = $fileInfo['file_name'];
        $thumbnailPath = $fileInfo['thumbnail_path'] ?? '';
        
        if (in_array($mimeType, $this->supportedImageTypes)) {
            // Image preview with lightbox
            return $this->getImageHtml($filePath, $thumbnailPath, $fileName);
        } elseif (in_array($mimeType, $this->browserSupportedTypes)) {
            // Browser-supported file preview
            return $this->getBrowserSupportedHtml($filePath, $mimeType, $fileName);
        } else {
            // Download link with icon
            return $this->getDownloadHtml($filePath, $fileName);
        }
    }

    /**
     * Generate HTML for image files
     */
    private function getImageHtml($filePath, $thumbnailPath, $fileName) {
        return "
            <div class='attachment-preview'>
                <a href='" . htmlspecialchars($filePath) . "' 
                   class='lightbox' 
                   data-caption='" . htmlspecialchars($fileName) . "'>
                    <img src='" . htmlspecialchars($thumbnailPath) . "' 
                         alt='" . htmlspecialchars($fileName) . "' 
                         class='thumbnail'>
                </a>
            </div>";
    }

    /**
     * Generate HTML for browser-supported files
     */
    private function getBrowserSupportedHtml($filePath, $mimeType, $fileName) {
        $iconClass = $this->getFileIconClass($mimeType);
        return "
            <div class='attachment-preview'>
                <a href='" . htmlspecialchars($filePath) . "' 
                   target='_blank' 
                   class='preview-link'>
                    <i class='fa " . $iconClass . "'></i>
                    <span class='filename'>" . htmlspecialchars($fileName) . "</span>
                </a>
            </div>";
    }

    /**
     * Generate HTML for downloadable files
     */
    private function getDownloadHtml($filePath, $fileName) {
        return "
            <div class='attachment-preview'>
                <a href='" . htmlspecialchars($filePath) . "' 
                   download='" . htmlspecialchars($fileName) . "' 
                   class='download-link'>
                    <i class='fa fa-file'></i>
                    <span class='filename'>" . htmlspecialchars($fileName) . "</span>
                </a>
            </div>";
    }

    /**
     * Get appropriate Font Awesome icon class based on mime type
     */
    private function getFileIconClass($mimeType) {
        $iconMap = [
            'application/pdf' => 'fa-file-pdf',
            'text/plain' => 'fa-file-text',
            'text/html' => 'fa-file-code',
            'audio/mpeg' => 'fa-file-audio',
            'video/mp4' => 'fa-file-video',
            'image/svg+xml' => 'fa-file-image'
        ];

        return $iconMap[$mimeType] ?? 'fa-file';
    }
}
```

### 2. CSS Styling
Create a new file `attachments.css`:

```css
.attachment-preview {
    display: inline-block;
    margin: 10px;
    text-align: center;
}

.thumbnail {
    max-width: 150px;
    max-height: 150px;
    object-fit: contain;
    border: 1px solid #ddd;
    padding: 5px;
}

.preview-link, .download-link {
    text-decoration: none;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.fa {
    font-size: 48px;
    margin-bottom: 10px;
}

.filename {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
```

### 3. JavaScript Implementation
Add this script to your page:

```javascript
document.addEventListener('DOMContentLoaded', function() {
    new SimpleLightbox('.lightbox', {
        captionsData: 'caption',
        animationSpeed: 200
    });
});
```

### 4. HTML Structure
Include required dependencies in your HTML head:

```html
<!DOCTYPE html>
<html>
<head>
    <!-- Font Awesome for file icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- SimpleLightbox for image previews -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplelightbox/2.10.3/simple-lightbox.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplelightbox/2.10.3/simple-lightbox.min.js"></script>
    
    <!-- Your custom CSS -->
    <link rel="stylesheet" href="path/to/attachments.css">
</head>
<body>
    <!-- Your content here -->
</body>
</html>
```

### 5. Usage Example
In your PHP file:

```php
<?php
require_once 'AttachmentHandler.php';

$handler = new AttachmentHandler();

// When uploading a file
if ($fileIsImage) {
    $handler->createImageThumbnail($uploadedFilePath, $thumbnailPath);
}

// When displaying attachments
foreach ($attachments as $attachment) {
    echo $handler->getAttachmentHtml([
        'file_path' => $attachment->path,
        'file_name' => $attachment->name,
        'mime_type' => $attachment->mime_type,
        'thumbnail_path' => $attachment->thumbnail_path // for images only
    ]);
}
?>
```

## Features

1. **Image Handling**
   - Automatic thumbnail generation
   - Lightbox preview on click
   - Responsive image display

2. **Browser-Supported Files**
   - PDF, text, HTML, audio, and video preview
   - Opens in new tab
   - Appropriate file type icons

3. **Unsupported Files**
   - Direct download functionality
   - Generic file icon
   - Filename display

4. **Security Features**
   - HTML escaping for all output
   - Mime type validation
   - Secure file handling

5. **UI/UX Features**
   - Responsive design
   - Visual file type indicators
   - Clean, consistent layout
   - Hover effects
   - Truncated filenames

## Maintenance and Extensibility

To add support for additional file types:
1. Add new mime types to either `$supportedImageTypes` or `$browserSupportedTypes`
2. Add corresponding icon classes in `getFileIconClass()`
3. Modify CSS as needed for new file type displays

## Troubleshooting

Common issues and solutions:

1. **Thumbnails not generating**
   - Ensure GD library is installed
   - Check file permissions
   - Verify image file is valid

2. **Icons not displaying**
   - Verify Font Awesome CSS is properly included
   - Check icon class names match Font Awesome version

3. **Lightbox not working**
   - Ensure SimpleLightbox JS/CSS are loaded
   - Verify class names match SimpleLightbox initialization
