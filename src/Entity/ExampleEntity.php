<?php

namespace BaclucC5Crud\Entity;

use BaclucC5Crud\Lib\GetterTrait;
use BaclucC5Crud\Lib\SetterTrait;
use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Class ExampleEntity.
 *
 * @IgnoreAnnotation("package")
 *  Concrete\Package\BaclucC5Crud\Src
 *
 * @Entity
 *
 * @Table(name="btExampleEntity")
 */
class ExampleEntity implements Identifiable {
    use GetterTrait;
    use SetterTrait;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $intcolumn;

    /**
     * @Column(type="string", nullable=true)
     */
    protected $dropdowncolumn;

    /**
     * @var ReferencedEntity
     *
     * @ManyToOne(targetEntity="BaclucC5Crud\Entity\ReferencedEntity")
     */
    protected $manyToOne;

    /**
     * @var ReferencedEntity[]
     *
     * @ManyToMany(targetEntity="BaclucC5Crud\Entity\ReferencedEntity")
     */
    protected $manyToMany;

    /**
     * @var int
     *
     * @Id @Column(type="integer")
     *
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(type="string")
     */
    private $value;

    /**
     * @Column(type="date", nullable=true)
     */
    private $datecolumn;

    /**
     * @Column(type="datetime", nullable=true)
     */
    private $datetimecolumn;

    /**
     * @Column(type="text")
     */
    private $wysiwygcolumn;

    /**
     * ExampleEntity constructor.
     */
    public function __construct() {
        $this->manyToMany = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public static function getIdFieldName(): string {
        return 'id';
    }
}
