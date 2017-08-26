<?php

if (!function_exists('lllist')) {
    /**
     * Helper that returns a instance of {@link \Lllist\Builder}
     * @param string $defaultSeparator
     * @param null|string $defaultLastSeparator
     * @return \Lllist\Builder
     */
  function lllist(string $defaultSeparator = " ", ?string $defaultLastSeparator = null)
  {
      return new \Lllist\Builder($defaultSeparator, $defaultLastSeparator);
  }
}