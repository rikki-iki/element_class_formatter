<?php

namespace Drupal\element_class_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\link\Plugin\Field\FieldFormatter\LinkFormatter;
use Drupal\Core\Template\Attribute;

/**
 * Formatter for displaying links in an HTML list.
 *
 * @FieldFormatter(
 *   id="link_list_class",
 *   label="Link list (with class)",
 *   field_types={
 *     "link",
 *   }
 * )
 */
class LinkListClassFormatter extends LinkFormatter {

  use ElementClassTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $defaut_settings = parent::defaultSettings() + [
      'list_type' => 'ul',
    ];

    return ElementClassTrait::linkClassDefaultSettings($defaut_settings);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    $class = $this->getSetting('class');

    $elements['list_type'] = [
      '#title' => $this->t('List type'),
      '#type' => 'select',
      '#options' => [
        'ul' => 'Un-ordered list',
        'ol' => 'Ordered list',
      ],
      '#default_value' => $this->getSetting('list_type'),
    ];

    return $this->linkClassSettingsForm($elements, $class);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $class = $this->getSetting('class');
    if ($tag = $this->getSetting('list_type')) {
      $summary[] = $this->t('List type: @tag', ['@tag' => $tag]);
    }

    return $this->linkClassSettingsSummary($summary, $class);
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $class = $this->getSetting('class');

    $attributes = new Attribute();
    if (!empty($class)) {
      $attributes->addClass($class);
    }

    return [
      [
        '#theme' => 'item_list',
        '#items' => $elements,
        '#list_type' => $this->getSetting('list_type'),
        '#attributes' => $attributes->toArray(),
      ],
    ];
  }

}
