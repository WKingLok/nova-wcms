<?php

declare(strict_types=1);

namespace Packages\HeaderMenu\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * @method static static PAGE()
 * @method static static EXTERNAL()
 */
final class HeaderMenuType extends Enum
{
    #[Description('Page')]
    const PAGE = 'page';

    #[Description('External Url')]
    const EXTERNAL = 'external';
}
