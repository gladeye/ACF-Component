## Creating Components

### StandardComponent
Creates a Component with a number of fields that can be used in Templates
### Constructor
```php
new StandardComponent(string $name, string $title)
```
- name:(required) Must be globally unique. Referenced when adding Components to Templates
- title:(required) Can be anything, currently overwritten by Templates so does not display anywhere

```php
StandardComponent::addField(string $type, string $name, string $label, array $options) 
```
Add a simple field to this component

- type:(required) The type of component eg text, allowed values are defined by Advanced Custom Fields. Can create a test field group using the ACF admin panel and export as php to see the possible field names, or can look at the acf source
- name:(required) Only needs to be unique to this Component. Used when referencing this fields value in the view
- label:(required) The title of the field visible in the admin
- options: an array of options is passed to acf on field construction, can be things like the toolbar of the wysiwyg, or image type restrictions

```php
StandardComponent::addRepeaterField(string $name, string $label, string $childComponent, int $min, int $max, array $options) 
```
Add a repeater field to this component, this can be done with addField, but this does the work to use a component for the repeated fields

- name:(required) Only needs to be unique to this component
- label:(required) The title of the field visible in the admin
- childComponent:(required) The name of another Component that the repeater should repeat
- min: The minimum number of repeats
- max: The maximum number of repeats
- options: Additional options to pass to acf for this field.

```php
StandardComponent::addCloneField(string $component, string $name, string $label, bool $inline = false, bool $prefix = true array $options) 
```
Adds another component as a field in this component

- component:(required) The name of another component to embed as a field in this component.
- name:(required) The name of this field, only needs to be unique to this component 
- label:(required) The title of this field in the admin.
- inline: Whether the embedded component should be visually separated from other fields. If true the fields in the component will show as standard fields in the parent component. In most cases this should stay as false
- prefix: Whether the fields in the database should be prefixed by the field name. In most cases this should stay as true, this prevents name conflicts when embedding components into other components
- options:(required) Additional options to pass to acf for this field 