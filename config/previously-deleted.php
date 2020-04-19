<?php

return [
    /*
     * Table to use for deleted attributes.
     *
     * Default: previously_deleted_attributes
     */
    'table' => 'previously_deleted_attributes',

    /*
     * Failed validation rule message.
     *
     * Default: The given :attribute is not allowed.
     */
    'failed_message' => 'The given :attribute is not allowed.',

    /*
     * Only store deleted attributes if the model uses soft-deletes
     * and has been force-deleted.
     *
     * Default: true
     */
    'ignore_soft_deleted' => true,
];
