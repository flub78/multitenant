# Include files

A WEB application requires the loading of CSS and javascript files.

## Global resources

They are resources shared by all tenants they are application specific.

These resources can be put in the public folder or one of the subdirectories.

Warning: Multitenant overwrite the asset function and the following code is rendered as the following URL.

    src="{{ asset('js/include.js') }}"

    http://abbeville.tenants.com/tenancy/assets/js/include.js

storage_path() and asset() helpers tenant-aware by modifying the paths they return.

To access global assets such as JS/CSS assets, you can use global_asset() and mix().

## Tenant resources

They are specific to each tenants and can be used for exemple to give a different look and feel to each tenant.

## Resources versus public

public is where a browser can access a resource. Resources are the sources that need to be compiled and or minified and the result is usually put in public.

