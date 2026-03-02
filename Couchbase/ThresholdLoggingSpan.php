<?php

namespace Couchbase;

use Couchbase\Exception\TracerException;
use Couchbase\Observability\StatusCode;

/**
 * @internal This class is not intended to be used directly. No parent spans should be provided to operations
 *  when using the default ThresholdLoggingTracer.
 */
class ThresholdLoggingSpan implements RequestSpan
{
    /**
     * @var resource
     */
    private $coreSpan;

    public function __construct($coreSpan)
    {
        $this->coreSpan = $coreSpan;
    }

    public function addTag(string $key, $value): void
    {
        $function = COUCHBASE_EXTENSION_NAMESPACE . '\\coreSpanAddTag';
        $function($this->coreSpan, $key, $value);
    }

    public function setStatus(StatusCode $statusCode): void
    {
    }

    public function end(?int $endTimestampNanoseconds = null): void
    {
        if (!is_null($endTimestampNanoseconds)) {
            throw new TracerException('ThresholdLoggingSpan does not support custom end timestamps');
        }
         $function = COUCHBASE_EXTENSION_NAMESPACE . '\\coreSpanEnd';
         $function($this->coreSpan);
    }

    /**
     * @return resource
     * @internal
     */
    public function core()
    {
        return $this->coreSpan;
    }
}
