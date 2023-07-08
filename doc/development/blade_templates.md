# Blade templates

The project uses the blade template engine to generate the views. It is flexible and already fully integrated in Laravel.

Index, create and edit blade templates can be generated with the [code generator](code_generation.md)

Blade templates have a relatively simple structure and just include a header, a footer and a navbar.

The BladeHelper class provides support to generate HTML blocks dynamically dependent on the data. See the BladeHelper class for the list of supported building blocks.

Example:

    <td> {!! Blade::picture("code_gen_type.picture", $code_gen_type->id, "picture", $code_gen_type->picture) !!}</td>

The combination of these three mechanisms, blade templates, code generation and Helper keeps the views relatively simple. 