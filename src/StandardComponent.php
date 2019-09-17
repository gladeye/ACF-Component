<?php
namespace Kethatril\ACFComponent;

class StandardComponent implements Component {
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $fields;
    /**
     * @var bool
     */
    private $flexible;

    /**
     * Component constructor.
     * @param string $name
     * @param string $title
     * @param bool $flexible
     */
    public function __construct(string $name, string $title, $flexible = false)
    {
        $this->name = $name;
        $this->title = $title;
        $this->flexible = $flexible;
    }

    /**
     * @param string $component
     * @param string $name
     * @param string $label
     * @param bool $inline
     * @param bool $prefix
     * @param array $options
     * @return $this
     */
    public function addCloneField(string $component, string $name, string $label, bool $inline = false, bool $prefix = true, array $options = []) {
        $this->fields[] = Template::createCloneField($component, $this->name, $name, $label, $inline, $prefix, $options);
        return $this;
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $label
     * @param array $options
     * @return $this
     */
    public function addField(string $type, string $name, string $label, array $options = []) {
        $this->fields[] = Template::createField($type, $this->name, $name, $label, $options);
        return $this;
    }

    /**
     * @param string $key
     * @param string $name
     * @param string $label
     * @param string $childComponent
     * @param int $min
     * @param int $max
     * @param array $options
     * @return $this
     */
    public function addRepeaterField(string $name, string $label, string $childComponent, int $min = 0, int $max = 0, array $options = []) {
        $this->fields[] = Template::createRepeaterField($this->name, $name, $label, $childComponent, $min, $max, $options);
        return $this;
    }


    public function getGroup() {
        return [
            'key' => "template_{$this->name}",
            'title' => $this->title,
            'active' => 0,
            'fields' => $this->fields
        ];
    }


    public function getFields() {
        return $this->fields;
    }

    public function getName() {
        return $this->name;
    }

    public function getTitle() {
        return $this->title;
    }
}