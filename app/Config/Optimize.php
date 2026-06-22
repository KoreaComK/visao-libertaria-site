<?php

namespace Config;

/**
 * Optimization Configuration.
 *
 * NOTE: This class does not extend BaseConfig for performance reasons.
 *       So you cannot replace the property values with Environment Variables.
 *
 * @immutable
 */
class Optimize
{
    public bool $configCacheEnabled = false;

    public bool $locatorCacheEnabled = false;
}
