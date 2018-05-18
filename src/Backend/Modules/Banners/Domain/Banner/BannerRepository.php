<?php

namespace Backend\Modules\Banners\Domain\Banner;

use Backend\Core\Engine\Model;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Backend\Modules\Banners\Domain\Banner\Exception\BannerNotFound;

class BannerRepository extends EntityRepository
{
    public function add(Banner $banner): void
    {
        // We don't flush here, see http://disq.us/p/okjc6b
        $this->getEntityManager()->persist($banner);
    }

    public function findAllActive(): array
    {
        return $this->findBy(
            ['status' => Status::active()],
            ['sequence' => 'ASC']
        );
    }

    /**
     * @param string $id
     * @return Banner if a banner was found
     * @throws \Exception
     */
    public function findOneById(string $id): Banner
    {
        if ($id === null) {
            throw BannerNotFound::forEmptyId();
        }

        /** @var Banner|null $banner */
        $banner = $this->findOneById($id);

        if ($banner === null) {
            throw BannerNotFound::forId($id);
        }

        return $banner;
    }

    /**
     * @param string $url
     * @return Banner if no banner was found
     * @throws \Exception
     */
    public function findOneByUrl(?string $url): Banner
    {
        if ($url === null) {
            throw BannerNotFound::forEmptyURL();
        }

        try {
            return $this->getEntityManager()->createQueryBuilder()
                ->select('c')
                ->from(Banner::class, 'c')
                ->innerJoin('c.translations', 'ct')
                ->innerJoin('c.meta', 'm')
                ->where('m.url = :url')
                ->setParameter('url', $url)
                ->getQuery()
                ->setMaxResults(1)
                ->getSingleResult();
        } catch (NoResultException $noResultException) {
            throw BannerNotFound::forURL($url);
        }
    }

    public function getNext(Banner $banner): ?Banner
    {
        return $this->findOneBySequence($banner->getSequence() + 1);
    }

    public function getNextSequence(): int
    {
        return (int) $this->getEntityManager()->createQueryBuilder()
            ->select('(COALESCE(MAX(c.sequence), -1) + 1) AS sequence')
            ->from(Banner::class, 'c')
            ->getQuery()->getSingleScalarResult();
    }

    public function getPrevious(Banner $banner): ?Banner
    {
        if ($banner->getSequence() === 0) {
            return null;
        }

        return $this->findOneBySequence($banner->getSequence() - 1);
    }

    /**
     * Retrieve the unique URL for an item
     *
     * @param string $url The URL to base on.
     * @param string $id The id of the item to ignore.
     *
     * @return string
     */
    public function getURL(string $url, string $id = null): string
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(b)')
            ->from(Banner::class, 'b')
            ->innerJoin('b.meta', 'm')
            ->where('m.url = :URL')
            ->setParameter('URL', $url);

        if ($id !== null) {
            $query
                ->andWhere('b.id != :id')
                ->setParameter('id', $id);
        }

        if ((int) $query->getQuery()->getSingleScalarResult() === 0) {
            return $url;
        }

        return $this->getURL(Model::addNumber($url), $id);
    }

    public function remove(Banner $banner): void
    {
        // We don't flush here, see http://disq.us/p/okjc6b
        $this->getEntityManager()->remove($banner);
    }
}
