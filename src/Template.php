<?php

namespace Kethatril\ACFComponent;

class Template {

    const WRAPPER = '__wrapper__';
    const VIEW = '__view__';
    const SIDEBAR_WRAPPER = '__sidebar-wrapper__';

    /**
     * @var string
     */
    protected $name;

    protected $group = [];

    protected $fields = [];

    public $components = [];

    public $extraComponents = [];


    /**
     * @var callback[]
     */
    public static $fieldTypes = [];


    public static $fieldNames = [];


    public static $componentTemplates = [];

    public static $positions = [
        'info' => [
            'title' => 'Details',
            'position' => 'normal',
            'menu_order' => 0
        ],
        'inline' => [
            'title' => 'Components',
            'position' => 'normal',
            'menu_order' => 0
        ],
        'auxiliary' => [
            'title' => 'Extra',
            'position' => 'side',
            'menu_order' => 0
        ],
        'sidebar' => [
            'title' => 'Sidebar Components',
            'position' => 'normal',
            'menu_order' => 1
        ]
    ];

    /**
     * @var Template[]
     */
    public static $templates = [];

    /**
     * @var string
     */
    private $title;
    /**
     * @var array
     */
    private $location;
    /**
     * @var array
     */
    private $options;


    private $wrapper;


    private $wrapperStack = [];
    /**
     * @var bool
     */
    public $wordpressTemplate;
    /**
     * @var string
     */
    public $handler;

    /**
     * @var array
     */
    public $templateData;


    /**
     * Group constructor.
     * @param string $name
     * @param string $title
     * @param array $location
     * @param array $options
     * @param bool $wordpressTemplate
     * @param string $handler
     */
    public function __construct(string $name, string $title, array $location, array $options = [], bool $wordpressTemplate = false, string $handler = 'handle')
    {
        $this->name = $name;
        $this->title = $title;
        $this->location = [[$location]];
        $this->options = $options;
        $this->wordpressTemplate = $wordpressTemplate;

        if($this->wordpressTemplate) {
            $this->addWordpressTemplate();
        }

        self::$templates[$this->name] = $this;


        $this->handler = $handler;
    }

    /**
     * @param string $component
     * @param string $key
     * @param string $title
     * @param string $location
     * @param array $options
     * @return $this
     * @internal param $name
     */
    public function addComponent(string $component, string $key, string $title, string $location = 'inline', array $options = []) {
        $this->fields[$location][] = [
            'key' => "field_{$this->name}_{$key}",
            'label' => $title,
            'name' => $key,
            'type' => 'clone',
            'display' => 'group',
            'prefix_name' => 1,
            'clone' => [
                "template_{$component}"
            ]

        ];
        $mod = [
            'component' => $component,
            'key' => $key,
            'options' => $options,
        ];
        if($location === 'inline') {
            if(!is_null($this->wrapper)) {
                $this->wrapper['children'][] = $mod;
            } else {
                $this->components[] = $mod;
            }
        } else {
            if(!isset($this->extraComponents[$location])) {
                $this->extraComponents[$location] = [];
            }
            $this->extraComponents[$location][$key] = $mod;
        }


        return $this;
    }

    public function addWrapper(string $type, array $data, callable $callback) {
        if($this->wrapper) {
            $this->wrapperStack[] = $this->wrapper;
        }
        $this->wrapper = array_merge($data, [
            'component' => $type,
            'children' => []
        ]);

        call_user_func($callback, $this);
        $wrapper = $this->wrapper;
        $this->wrapper = array_pop($this->wrapperStack);
        if(!is_null($this->wrapper)) {
            $this->wrapper['children'][] = $wrapper;
        } else {
            $this->components[] = $wrapper;
        }
        return $this;
    }


    public function addView($view) {
        $component = [
            'component' => self::VIEW,
            'view' => $view
        ];
        if(!is_null($this->wrapper)) {
            $this->wrapper['children'][] = $component;
        } else {
            $this->components[] = $component;
        }
        return $this;
    }


    /**
     * @param bool $hideContent
     * @return Template
     */
    public function apply($hideContent = true) {
        foreach($this->fields as $positionKey => $fields) {
            $position = self::$positions[$positionKey];
            $group = array_merge([
                'key' => "group_{$this->name}_{$positionKey}",
                'title' => $position['title'],
                'position' => $position['position'],
                'menu_order' => $position['menu_order'],
                'location' => $this->location,
                'fields' => $fields,
                'style' => 'seamless',
                'hide_on_screen' => $hideContent ? ['the_content'] : [],
            ], $this->options);
            add_action('acf/init', function() use($group) {
                acf_add_local_field_group($group);
            });
        }

        return $this;
    }


    private function addWordpressTemplate() {
        add_filter('theme_page_templates', function($templates) {
            $templates[$this->name] = $this->title;
            return $templates;
        });
    }

    /**
     * @param null|array $fields
     * @return Template
     */
    public function setDebug($fields = null) {
        if(env('DEMO_CONTENT', false)) {
            $path = base_path('resources' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . $this->name . '.json');
            if(file_exists($path)) {
                $jsonString = file_get_contents($path);
                $this->templateData = json_decode($jsonString,  JSON_OBJECT_AS_ARRAY);
            }
        }
        return $this;
    }

    /**
     * @param int|string $postId
     * @return array|bool
     */
    public function getTemplateData($postId) {
        $fields = get_fields($postId);
        if($fields === false) {
            $fields = [];
        }
        if(!empty($this->templateData)) {
            return array_merge_recursive($fields, $this->templateData);
        }
        return $fields;
    }


    /**
     * @param Component $component
     */
    public static function registerComponent(Component $component) {

        self::$componentTemplates[$component->getName()] = $component;

        add_action('acf/init', function() use($component) {
            acf_add_local_field_group($component->getGroup());
        });

    }



    /**
     * @param string $type
     * @param string $key
     * @param string $name
     * @param string $label
     * @param array $options
     * @return array
     */
    public static function createField(string $type, string $key, string $name, string $label, array $options = []) : array {
        $base = [
            'key' => "field_{$key}_{$name}",
            'name' => $name,
            'label' => $label,
            'type' => $type,
        ];
        return array_merge($options, $base );
    }

    /**
     * @param string $component
     * @param string $key
     * @param string $name
     * @param string $label
     * @param bool $inline
     * @param bool $prefix
     * @param array $options
     * @return array
     */
    public static function createCloneField(string $component, string $key, string $name, string $label, bool $inline = false, bool $prefix = true, array $options = []) : array {
        $base = [
            'key' => "field_{$key}_{$name}",
            'name' => $name,
            'label' => $label,
            'type' => 'clone',
            'prefix_name' => $prefix ? 1 : 0,
            'display' => $inline ? 'seamless' : 'group',
            'clone' => [
                "template_{$component}"
            ]
        ];

        return array_merge($options, $base );
    }

    /**
     * @param string $key
     * @param string $name
     * @param string $label
     * @param string $childComponent
     * @param int $min
     * @param int $max
     * @param array $options
     * @return array
     */
    public static function createRepeaterField(string $key, string $name, string $label, string $childComponent, int $min = 0, int $max = 0, array $options = []) : array {
        $base = [
            'key' => "field_{$key}_{$name}",
            'name' => $name,
            'label' => $label,
            'type' => 'repeater',
            'min' => $min,
            'max' => $max,
            'sub_fields' => [
                static::createCloneField($childComponent, "{$key}_{$name}", $childComponent, $label, true, false)
            ]
        ];
        return array_merge( $options, $base);
    }



}