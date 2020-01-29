<?php

namespace Drupal\bar\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @file
 * Render a node inside a block.
 */

/**
 * Provides a 'Node Embed' block.
 *
 * @Block(
 *   id = "node_embed_block",
 *   admin_label = @Translation("Node Embed"),
 *   category = @Translation("Content")
 * )
 */
class NodeEmbedBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a NodeEmbedBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityDisplayRepositoryInterface $entity_display_repository,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityDisplayRepository = $entity_display_repository;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_display.repository'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $block['content'] = t('Content not found');

    $config = $this->getConfiguration();

    if (isset($config['nid'])) {
      /** @var \Drupal\node\NodeStorageInterface $node_storage */
      $node_storage = $this->entityTypeManager()->getStorage('node');
      /** @var \Drupal\node\NodeInterface $node */
      $node = $node_storage->load($config['nid']);
      $view_mode = (isset($config['view_mode']) && !empty($config['view_mode']) ? $config['view_mode'] : 'full');
      $block['content'] = node_view($node, $view_mode);
    }

    return $block;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form = parent::blockForm($form, $form_state);

    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();

    // Add a form field to the existing block configuration form.
    $form['nid'] = [
      '#title' => t('Node'),
      '#description' => t('The node you want to display'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#default_value' => ((isset($config['nid']) && !empty($config['nid'])) ? Node::load($config['nid']) : NULL),
      '#required' => TRUE,
    ];

    // View modes.
    $options = [];
    $view_modes = $this->entityDisplayRepository->getAllViewModes();
    if (isset($view_modes['node'])) {
      foreach ($view_modes['node'] as $view_mode => $view_mode_info) {
        $options[$view_mode] = $view_mode_info['label'];
      }
    }

    $form['view_mode'] = [
      '#title' => t('View mode'),
      '#description' => t('Select the view mode you want your node to render in.'),
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => (isset($config['view_mode']) && !empty($config['view_mode']) ? $config['view_mode'] : 'full'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('nid', $form_state->getValue('nid'));
    $this->setConfigurationValue('view_mode', $form_state->getValue('view_mode'));
  }

}
