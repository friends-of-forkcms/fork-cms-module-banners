<?php

namespace Backend\Modules\Banners\Domain\Banner;

use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\DataGridDatabase;
use Backend\Core\Engine\Model;
use Backend\Core\Language\Language;
use Backend\Core\Language\Locale;

class BannerDataGrid extends DataGridDatabase
{
    public function __construct()
    {
        parent::__construct(
            'SELECT b.id, b.sequence, bt.title
             FROM Banner b
             INNER JOIN BannerTranslation bt
             WHERE bt.locale = :locale AND bt.bannerId = b.id
             GROUP BY b.id
             ORDER BY b.sequence',
            ['locale' => Locale::workingLocale()]
        );

        $this->enableSequenceByDragAndDrop();
        $this->setAttributes(['data-action' => 'BannerSequence']);

        if (Authentication::isAllowedAction('BannerEdit')) {
            // Define edit url
            $editUrl = Model::createUrlForAction('BannerEdit', null, null, ['id' => '[id]'], false);
            $this->setColumnURL('title', $editUrl);
            $this->addColumn('edit', null, Language::lbl('Edit'), $editUrl, Language::lbl('Edit'));
        }
    }

    public static function getHtml(): string
    {
        $dataGrid = new self();

        return (string) $dataGrid->getContent();
    }
}
