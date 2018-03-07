<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Tag;

/**
 * NoteRepository
 */
class NoteRepository extends \Doctrine\ORM\EntityRepository
{
    public function search($data, $page = 1) {

        $query = $this->createQueryBuilder('n');
        $query->leftJoin('n.category', 'c');
        // wyszukiwarka
        if(!empty($data['title'])) {
            $query->andWhere('n.title LIKE :title')->setParameter('title', '%' . $data['title'] . '%');
        }

        if(!empty($data['category'])) {
            $query->andWhere('n.category = :category')->setParameter('category', $data['category']);
        }

        if(!empty($data['category_name'])) {
            $query->andWhere('c.name LIKE :category_name')->setParameter('category_name', '%' . $data['category_name'] . '%');
        }

        if(!empty($data['tags'])) {
            $query->andWhere(':tag MEMBER OF n.tags')->setParameter('tag', $data['tags']);
        }

        // sortowanie
        if(!empty($data['sort'])) {
            $params = explode('.', $data['sort']);

            if (isset($params[1])) {
                $query->orderBy('n.'.$params[0], $params[1]);
            } else {
                $query->orderBy('n.'.$params[0]);
            }

        } else {
            $query->orderBy('n.id', 'ASC');
        }

        // paginacja
        $offset = ($page * 10) - 10;

        $query->setFirstResult($offset)
            ->setMaxResults(10);

        return $query->getQuery()->getResult();
    }

    public function getTotalNotes() {
        return $this->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
