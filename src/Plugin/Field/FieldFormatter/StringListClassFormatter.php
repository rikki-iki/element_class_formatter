<?php

namespace Drupal\element_class_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Template\Attribute;

/**
 * Formatter for displaying links in an HTML list.
 *
 * @FieldFormatter(
 *   id="string_list",
 *   label="List (with class)",
 *   field_types={
 *     "string",
 *     "string_long",
 *   }
 * )
 */
class StringListClassFormatter extends FormatterBase {

  use ElementClassTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $defaut_settings = parent::defaultSettings() + [
      'tag' => 'ul',
    ];

    return ElementClassTrait::linkClassDefaultSettings($defaut_settings);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    $class = $this->getSetting('class');

    $elements['tag'] = [
      '#title' => $this->t('List type'),
      '#type' => 'select',
      '#options' => [
        'ul' => 'Un-ordered list',
        'ol' => 'Ordered list',
      ],
      '#default_value' => $this->getSetting('tag'),
    ];

    return $this->linkClassSettingsForm($elements, $class);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $class = $this->getSetting('class');
    if ($tag = $this->getSetting('tag')) {
      $summary[] = $this->t('List type: @tag', ['@tag' => $tag]);
    }

    return $this->linkClassSettingsSummary($summary, $class);
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $attributes = new Attribute();
    $class = $this->getSetting('class');
    if (!empty($class)) {
      $attributes->addClass($class);
    }
    foreach ($items as $delta => $item) {
      $elements[$delta] = $item->getValue()['value'];
    }

    return [
      [
        '#theme' => 'item_list',
        '#items' => $elements,
        '#list_type' => $this->getSetting('tag'),
        '#attributes' => $attributes->toArray(),
      ],
    ];
  }

}
