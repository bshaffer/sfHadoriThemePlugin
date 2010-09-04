[?php if ($helper->activeFilters()): ?]
  <span class="filter-message">
    These results have been filtered.
    <strong>
      [?php echo link_to(__('Click Here', array(), 'sf_admin'), '<?php echo $this->getUrlForAction('collection') ?>',
                   array('action' => 'filter'),
                   array('query_string' => '_reset&for_action=' . $sf_params->get('action'), 
                         'method' => 'post', 'class' => 'link-to')) ?]
    </strong>
    to show all the records.
  </span>
[?php endif; ?]
