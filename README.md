# Oliva Events for WonderCMS

By Steve Alink for Oliva Solutions.

OlivaEvents is a simple WonderCMS plugin that shows unavailable dates on the frontend.

## Overview
This plugin allows you to enter dates (see below the format). Using a switch in the settings tab, you can only show dates  
that are possible or the reverse, only as dates there is no option. Just test this setting for the best result.  

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
<img width="1142" height="765" alt="WcmsOlivaEventsPreviewSettings030" src="https://github.com/user-attachments/assets/eceb3a2f-5ccc-414e-80ed-2c0f0bf87782" />

## Download the plugin via
```text
https://raw.githubusercontent.com/SteveAlink/oliva-events/main/wcms-modules.json
```

## Versions
v0.4.0 01-05-2026 Change in frontend  
v0.3.0 01-05-2026 One is now able to show the available days or the not available date  
v0.2.0 01-05-2026 Date fromatting changed on front end  
v0.1.0 01-05-2026 Initial version

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

Version 0.4.0 Has a number of minor changes

- Color coding on rows of dates on front end now have better distinction 
