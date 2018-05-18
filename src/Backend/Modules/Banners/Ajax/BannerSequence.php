<?php

namespace Backend\Modules\Banners\Ajax;

use Backend\Core\Engine\Base\AjaxAction;
use Backend\Modules\Banners\Domain\Banner\Command\ReSequenceBanners;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reorder banners
 */
class BannerSequence extends AjaxAction
{
    public function execute(): void
    {
        parent::execute();

        // get parameters
        $newIdSequence = trim($this->getRequest()->request->get('new_id_sequence'));

        // list id
        $ids = (array) explode(',', rtrim($newIdSequence, ','));

        $this->get('command_bus')->handle(new ReSequenceBanners($ids));

        // success output
        $this->output(Response::HTTP_OK, null, 'sequence updated');
    }
}
