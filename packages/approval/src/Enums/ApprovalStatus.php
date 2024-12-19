<?php

declare(strict_types=1);

namespace Packages\Approval\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * @method static static ARCHIVE()
 * @method static static DRAFT()
 * @method static static PENDING()
 * @method static static APPROVED()
 * @method static static REJECTED()
 */
final class ApprovalStatus extends Enum
{
    #[Description('Archive')]
    const ARCHIVE = 'archive';

    #[Description('Draft')]
    const DRAFT = 'draft';

    #[Description('Pending')]
    const PENDING = 'pending';

    #[Description('Approved')]
    const APPROVED = 'approved';

    #[Description('Rejected')]
    const REJECTED = 'rejected';
}
