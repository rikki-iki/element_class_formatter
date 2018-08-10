<?php

namespace Drupal\element_class_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Link;

/**
 * A field formatter for wrapping text with a class.
 *
 * @FieldFormatter(
 *   id = "wrapper_class",
 *   label = @Translation("Wrapper (with class)"),
 *   field_types = {
 *     "string",
 *     "string_long",
 *     "text",
 *     "text_long",
 *     "text_with_summary"
 *   }
 * )
 */
class WrapperClassFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $wrapper_options = [
      'span' => 'span',
      'div' => 'div',
      'p' => 'p',
    ];
    foreach (range(1, 5) as $level) {
      $wrapper_options['h' . $level] = 'H' . $level;
    }

    $form['tag'] = [
      '#title' => $this->t('Tag'),
      '#type' => 'select',
      '#description' => $this->t('Select the tag which will be wrapped around the text.'),
      '#options' => $wrapper_options,
      '#default_value' => $this->getSetting('tag'),
    ];

    $form['class'] = [
      '#title' => $this->t('Element class'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('class'),
    ];

    $form['linked'] = [
      '#title' => $this->t('Link to the Content'),
      '#type' => 'checkbox',
      '#description' => $this->t('Wrap the text with a link to the content.'),
      '#default_value' => $this->getSetting('linked'),
    ];

    $form['linked_class'] = [
      '#title' => $this->t('Link class'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('linked_class'),
      '#states' => [
        'visible' => [
          ':input[name$="[linked]"]' => ['checked' => TRUE],
        ],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return parent::defaultSettings() + [
      'tag' => 'h2',
      'class' => '',
      'linked' => '0',
      'linked_class' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    if ($tag = $this->getSetting('tag')) {
      $summary[] = $this->t('Wrapper: @tag', ['@tag' => $tag]);
    }
    if ($class = $this->getSetting('class')) {
      $summary[] = $this->t('Class: @class', ['@class' => $class]);
    }
    if ($linked = $this->getSetting('linked')) {
      $summary[] = $this->t('Link: @linked', ['@linked' => $linked ? 'yes' : 'no']);

      if ($linked_class = $this->getSetting('linked_class')) {
        $summary[] = $this->t('Link class: @linked_class', ['@linked_class' => $linked_class]);
      }
    }
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode = NULL) {
    $elements = [];
    $attributes = new Attribute();
    $classes = $this->getSetting('class');
    if (!empty($classes)) {
      $attributes->addClass($classes);
    }

    $parent = $items->getParent()->getValue();
    foreach ($items as $delta => $item) {
      $text = $item->getValue()['value'];
      if ($this->getSetting('linked') && $parent->urlInfo()) {
        $link_attributes = new Attribute();
        $link_classes = $this->getSetting('linked_class');
        if (!empty($link_classes)) {
          $link_attributes->addClass($link_classes);
        }
        $link = Link::fromTextAndUrl($text, $parent->urlInfo())->toRenderable();
        $link['#attributes'] = $link_attributes->toArray();
        $text = render($link);
      }
      $elements[$delta] = [
        '#type' => 'html_tag',
        '#tag' => $this->getSetting('tag'),
        '#attributes' => $attributes->toArray(),
        '#value' => $text,
      ];
    }
    return $elements;
  }

}
