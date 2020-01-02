<?php


namespace BasicTablePackage\Entity;

use BasicTablePackage\Lib\GetterTrait;
use BasicTablePackage\Lib\SetterTrait;
use Concrete\Package\BaclucExampleBasicTablePackage\Src\Example;
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
 * Class ExampleEntity
 * @IgnoreAnnotation("package")
 *  Concrete\Package\BasicTablePackage\Src
 * @Entity
 * @Table(name="btExampleEntity")
 */
class ExampleEntity
{
    use GetterTrait, SetterTrait;
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     * @Column(type="string")
     */
    private $value;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $intcolumn;

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
     * @Column(type="string", nullable=true)
     */
    protected $dropdowncolumn;

    /**
     * @var ReferencedEntity
     * @ManyToOne(targetEntity="BasicTablePackage\Entity\ReferencedEntity")
     */
    protected $manyToOne;

    /**
     * @var ReferencedEntity[]
     * @ManyToMany(targetEntity="BasicTablePackage\Entity\ReferencedEntity")
     */
    protected $manyToMany;

    /**
     * ExampleEntity constructor.
     */
    public function __construct()
    {
        $this->manyToMany = new ArrayCollection();
    }


}