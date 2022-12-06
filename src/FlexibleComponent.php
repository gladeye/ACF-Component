<?php

namespace Kethatril\ACFComponent;

class FlexibleComponent implements Component {

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
    private $components;
    /**
     * @var int
     */
    private $min;
    /**
     * @var int
     */
    private $max;
    /**
     * @var array
     */
    private $options;


    public function __construct(string $name, string $title, array $components, int $min = 0, int $max = 0, array $options = [])
    {

        $this->name = $name;
        $this->title = $title;
        $this->components = $components;
        $this->min = $min;
        $this->max = $max;
        $this->options = $options;
    }

    public function getFields() {
        $layouts = [];
        foreach($this->components as $key => $field) {
            $layouts[] = [
                'key' => "layout_{$this->name}_{$field['name']}",
                'name' => $field['name'],
                'label' => $field['title'],
                'min' => isset($field['min']) ? $field['min'] : 0,
                'max' => isset($field['max']) ? $field['max'] : 0, 
                'display' => "block",
                'sub_fields' => [
                    [
                        'key' => "field_clone_{$this->name}_{$field['name']}_{$key}",
                        'label' => '',
                        'name' => $field['name'],
                        'type' => 'clone',
                        'prefix_name' => 0,
                        'clone' => [
                            "template_{$key}"
                        ]

                    ]
                ],

            ];
        }
        

        $options = ['min' => $this->min, 'max' => $this->max, 'layouts' => $layouts];
        if(is_array($this->options) && count($this->options) > 0){
            $options = array_merge($options, $this->options);
        }
        
        return [Template::createField('flexible_content', "flexible_{$this->name}", $this->name, $this->title, $options)];
    }

    public function getGroup() {
        return [
            'key' => "template_{$this->name}",
            'title' => $this->title,
            'active' => 0,
            'fields' => $this->getFields()
        ];
    }

    public function getName() {
        return $this->name;
    }

    public function getTitle() {
        return $this->title;
    }
}
