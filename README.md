# Oliva Events for WonderCMS
------------------------------------------------
### DO NOT USE PLUGIN
### BLANK PAGE IS ONLY RESULT
### WIP
### SORRY FOR ANY INCONVINIENCE
------------------------------------------------

By Steve Alink for Oliva Solutions.

OlivaEvents is a simple WonderCMS plugin that shows dates and the action for those dates on the frontend.

## Overview
This plugin allows you to enter dates (see below the format). Using a switch in the settings tab, you can only show dates  
that are possible or the reverse, only as dates where it is not possible to do something (like store closed or holiday).  
Just test this setting for the best result.  
With the display mode setting one is able to either show the events in the footer or on a page (could be multiple).  
It is possible to hide all dates that are in the past.   

Example storage format:

```text
2026-05-12|Holiday,2026-05-13,2026-05-20|On tour
```
or

```text
2026-05-12|Holiday
2026-05-13
2026-05-20|On tour
```

## Preview of the settings for this plugin:
<img width="1120" height="964" alt="WcmsOlivaEventsPreviewSettings050" src="https://github.com/user-attachments/assets/8e142482-d9d1-4204-a810-3436a5a130df" />


## Preview of the frontend using the placeholder
<img width="2025" height="979" alt="WcmsOlivaEventsFrontEnd" src="https://github.com/user-attachments/assets/8f0e79f4-1ca0-4449-a85e-c8863698a660" />
In the red box, the rendered calendar.

## Download the plugin via
```text
https://raw.githubusercontent.com/SteveAlink/oliva-events/main/wcms-modules.json
```

## Versions
v0.6.0 02-05-2026 Show correct message if no dates (un)available  
v0.5.1 02-05-2026 Change as site broke of incorrect coding  
v0.5.0 02-05-2026 Including ``` {{oliva-events}} ``` to use on any page  
v0.4.0 01-05-2026 Change to include a description per day   
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

Version 0.4.0 Added optional descriptions for dates:

- Supports `YYYY-MM-DD|Description`
- Keeps plain `YYYY-MM-DD` dates working
- Supports comma-separated dates and one date per line
- Shows the description below the formatted date on the frontend
- Color coding on rows of dates on front end now have better distinction

Version 0.5.0 Added flexible placement and past-date filtering:

- Added setting to hide past dates
- Added placement mode setting
- Supports automatic footer rendering
- Supports placeholder rendering with `{{oliva-events}}`
- Added CSS/JS cache busting using version query strings

Version 0.5.1 Change code as server error occured

Version 0.6.0 Show correct message if no dates

- All language files updated
- New message is shown in front end if there are no dates available (not entered or all in the past)
