<?php
namespace Atlb\Hades;

function customize_register_attributes_groups($wp_customize)
{
    $attributes = json_decode(file_get_contents(__DIR__ . '/../assets/attributes.json'), true);
    $attributes_groups = json_decode(file_get_contents(__DIR__ . '/../assets/attributes_groups.json'), true);
    ksort($attributes);
    ksort($attributes_groups);
    $wp_customize->add_panel(
        $id = 'hades_attributes_groups',
        $args = array(
            'capability'=> 'edit_theme_options',
            'description' => 'Réglages spécifiques aux attributs des offres Hadès',
            'priority' => 1,
            'theme_supports' => '',
            'title' => 'Attributs Hadès'
        )
    );
    foreach ($attributes_groups as $attributes_group) {
        $wp_customize->add_section(
            $id = $attributes_group['id'],
            $args = array(
                'panel' => 'hades_attributes_groups',
                'priority' => 1,
                'title' => $attributes_group['label']
            )
        );
        ksort($attributes[$attributes_group['id']]);
        foreach ($attributes[$attributes_group['id']] as $attribute) {
            $wp_customize->add_setting(
                $id = $attributes_group['id'] . '_' . $attribute['id'],
                $args = array(
                    'capability' => 'edit_theme_options',
                    'type' => 'theme_mod',
                )
            );
            $wp_customize->add_control(
                $id = $attributes_group['id'] . '_' . $attribute['id'],
                $args = array(
                    'label' => $attribute['label'],
                    'section' => $attributes_group['id'],
                    'settings' => $attributes_group['id'] . '_' . $attribute['id'],
                    'type' => 'checkbox',
                    'default' => true
                )
            );
        }
    }
}
