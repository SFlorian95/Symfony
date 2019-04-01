<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment implements \JsonSerializable
{/*
 * JsonSerializable : class native du php qui permet de lister les propriétés 
 d'un objet accessibles suit à l'utilisation de la fonction json_encode
 * la fonction jsonSerialize est obligatoire lors de l'utilisation de JsonSerializable
 */
    public function jsonSerialize():array {
        // envoie un tableau associatif avec la listes des propriétés assecibles
        return[
            'content' => $this->getContent(),
            'datetime' => $this->getDatetime()
        ];
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;
    
    /**
     * Plusieurs commentaires sont reliés à un produit 
     * NE PAS OUBLIER : ajouter l'alias ORM
     * JoinColumn : nom de colonne contenant la clé étrangère
     * refencedColumnName : colonne de la clé primaire dans l'entité en relation (dans doctrine, c'est souvent id)
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="comments")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
