<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\BookRepository;
use Override;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BooksAuthorProvider implements ProviderInterface
{
	public function __construct(
		private readonly BookRepository $bookRepository
	)
	{}
	#[Override]
	public function provide(
		Operation $operation, 
		array $uriVariables = [], 
		array $context = []
	): array
	{
		$author = $uriVariables['author'];
		$books = $this->bookRepository->findBookByAuthor($author);
		if($books === []){
			throw new NotFoundHttpException('Auteur non trouvé');
		}
		return $books;
	}
}