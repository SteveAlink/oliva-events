# OlivaEvents for WonderCMS

By Steve Alink for Oliva Solutions.

OlivaEvents is a simple WonderCMS plugin that shows unavailable dates on the frontend.

## Overview

This first version is deliberately small:

- Backend tab: Oliva Events  
- Field: unavailable dates  
- Storage: comma-separated text  
- Frontend: simple list marking dates as unavailable

Version 0.2.0 is mainly an Improved frontend display:

- Groups unavailable dates by month  
- Formats dates based on visitor language  
- Highlights today when today is unavailable  
- Improves responsive styling  

Version 0.3.0 Comes with an Added display mode switch:

- Show dates as unavailable
- Show dates as available
- Dates can now be entered comma-separated or one date per line
- Frontend styling changes based on selected display mode

Example storage format:

```text
2026-05-12,2026-05-13,2026-05-20
```
or

```text
2026-05-12
2026-05-13
2026-05-20
```

## Preview of the settings for this plugin:
<img width="1123" height="696" alt="WcmsOlivaEventsPreviewSettings" src="https://github.com/user-attachments/assets/a9b84c09-65ea-41ca-88a1-d83c4501c2a6" />

## Download the plugin via
```text
https://raw.githubusercontent.com/SteveAlink/oliva-events/main/wcms-modules.json
```

## Versions
v0.3.0 01-05-2026 One is now able to show the available days or the not available date  
v0.2.0 01-05-2026 Date fromatting changed on front end  
v0.1.0 01-05-2026 Initial version
