<?php

interface BIS2BIS_Changelog_Interfaces_LogInterface
{
    const TABLE = 'changelog_log';

    const ID = 'id';
    const LOG = 'log';
    const CREATEDAT = 'created_at';
    const UPDATEDAT = 'updated_at';

    const LOGRESOURCE = 'changelog/log';
}