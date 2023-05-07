<p align="center">
  <a href="https://stefanoalletti.wordpress.com/">
    <img src="https://stefanoalletti.files.wordpress.com/2022/02/symfony_plus_ddd.png"/>
  </a>
</p>

<h1 align="center">
  DDD, Hexagonal Architecture & CQRS with Symfony and Doctrine
</h1>

<p align="center">
  Example of a <strong>Symfony application using Domain-Driven Design (DDD) and Command Query Responsibility Segregation
  (CQRS) principles</strong> keeping the code as simple as possible.
</p>

## Environment Setup

### Needed tools

1. [Install Docker](https://www.docker.com/get-started)
2. Clone this project: `git clone https://github.com/salletti/symfony-ddd-example.git`
3. Move to the project folder: `cd symfony-ddd-example`

### Application execution

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker-compose build --pull --no-cache` to build fresh images
3. Run `docker-compose up` (the logs will be displayed in the current shell)
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker-compose down --remove-orphans` to stop the Docker containers.

## Project explanation

A very simple application (absolutely not complete) to manage a blog. The goal is to show how to initialize a project with a DDD structure and how to make the different Bounded Contexts communicate.

### Structure 

```scala
$ tree -L 4 src

src
|-- Blog // Company subdomain / Bounded Context: Features related to one of the company business lines / products
|   `-- Post // Some Module inside the Mooc context
|       |-- Application
|       |   |-- Controller // Inside the application layer all is structured by actions
|       |   |   |-- Api
|       |   |   |   |-- GetArticlesController
|       |   |   |   |-- PostArticleController.php
|       |   |   |   |-- PostCommentController.php
|       |   |-- Event 
|       |   |-- EventSubscriber 
|       |   |-- Model // The Data transformer objects for CQRS 
|       |   |   |-- CreateArticleCommand.php 
|       |   |   |-- CreateCommandCommand.php 
|       |   |   |-- FindArticleQuery.php 
|       |   |-- ParamConverter 
|       |   |-- Service (the applications layer services) 
|       |-- Domain
|       |   |-- Entity (The entities and the value objects) 
|       |   |   |-- Article.php // The Aggregate Root of the Bounded Context
|       |   |   |-- ArticleId.php // Value Object   
|       |   |   |-- AuthorId.php // Value Object   
|       |   |   |-- Comment.php // Entity that depends from Aggregate Root
|       |   |   |-- CommentId.php // Value Object 
|       |   |   |-- Email.php // Value Object  
|       |   |-- Event // Domain Events
|       |   |   |-- ArticleCreatedEvent.php
|       |   |   |-- CommentCreatedEvent.php
|       |   |-- Repository 
|       |   |   |-- ArticleRepositoryInterface.php 
|       |   |   |-- CommentRepositoryInterface.php 
|       `-- Infrastructure // The infrastructure layer
|           |-- DoctrineMapping
|           |   |-- Article.orm.xml
|           |   |-- Comment.orm.xml
|           |   |-- Email.orm.xml
|           `-- Repository (the concrete repositories)
|               `--ArticleRepository.php // An implementation of the repository
|               `--CommentRepository.php // An implementation of the repository
```

### Hexagonal Architecture
This projects follows the Hexagonal Architecture pattern.<br>
The application layer can only use domain implementations and the infrastructure layer must implement domain interfaces in order to be completely independent.

For example the repository which is closely related to Doctrine and which is located in the infrastructure layer implements a domain interface.

<pre>
//App\Blog\Post\Domain\Repository\ArticleRepositoryInterface

interface ArticleRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function save(Article $article): void;
}
</pre>

### CQRS
Inside the Application / Model directory of the Bounded Contexts there are the "Command" or "Query" depending on the action to be performed.
These are dispatched via the Symfony message bus which will forward them to the associated service handler.

Example of Command: 

<pre>
//App\Blog\Post\Application\EventSubscriber\OnPublicationApprovedEventSubscriber

$createArticleCommand = new CreateArticleCommand();
$createArticleCommand->setTitle($event->getTitle());
$createArticleCommand->setBody($event->getBody());
$createArticleCommand->setAuthor($event->getAuthor());
$createArticleCommand->setCategory($event->getCategoryId());

$this->messageBus->dispatch($createArticleCommand);
</pre>

Example of Query
<pre>
//App\Blog\Post\Application\Controller\Api\GetArticleController

$article = $this->handle(new FindArticleQuery($id));
</pre>

### Repository pattern
Our repositories try to be as simple as possible usually only containing 2 methods `save` and `find`.

### Bounded Contexts

There are three different Bounded Contexts:
* Post: It takes care of managing everything related to an article.
* User: Here you should find everything related to user management.
* Category: This is where you can create categories, edit or delete them (even if only the creation is implemented).

#### Aggregates
Each BC has one and only one Aggregate Root.

An Aggregate is a cluster of associated object that we treat as a single unit. Each Aggregate has a single root Entity and a boundary that marks what is inside and what is outside the Aggregate.

A `Comment` object only really make sense in the context of a `Article` because we can’t have a `Comment` without a `Article`.

The root Entity is the only Entity that is globally accessible in the application. In the example from above, `Article` would be the root Entity.

Inside the boundary we would have all the associated objects of this Aggregate. For example, we would have the `Comment`Entity as well as any other Entities or Value Objects that make up the Aggregate.

The `Comment` object can hold references to each other internally to the Aggregate, but no other external object can hold a reference to any object internally to the Aggregate that is not the root Entity.

The only way to access the `Comment` Entity is to retrieve the Article root Entity and traverse it’s associated objects.


#### Communication between Bounded Contexts

The BCs must be independent, this means that they must not know anything about the other BCs.
For this reason, communication between BCs should ideally be done with an event-driven system.

In our example, when we want to create an article, the payload contains the category slug and the user id. But how do we get the category id and make sure the user id is correct? This data is managed in other bounded contexts.

Below is the sequence diagram showing the steps to publish an article.
<p align="center">
  <a href="https://stefanoalletti.wordpress.com/">
    <img src="https://stefanoalletti.files.wordpress.com/2022/02/capture-decc81cran-2022-02-25-acc80-22.11.01.png"/>
  </a>
</p>

An `OnPublicationRequestedEvent`is dispatched and listened in the user BC which check if the user exists.
The `UserVerifiedEvent`event is then dispatched and listened in the category BC which retrieves the category id by the slug. 
A new `OnPublicationApprovedEvent`is then dispatched and listened in the Post BC. 
Now the creation of the article can be done.

### How to use

1) <pre>$ docker-compose exec php sh</pre>
2) Create user: <pre>$ bin/console app:create-user s.alletti@gmail.com p4$$word ROLE_EDITOR</pre>
3) Create category:
    <pre>POST https://localhost/api/categories/</pre>
    <pre>
    {
        "name": "Sport",
        "slug": "sport"
    }
   </pre>
4) Create Article: 
    <pre>POST https://localhost/api/articles/</pre>
    <pre>
    {
        "title": "article",
        "body": "body",
        "author": *author_id*,
        "categorySlug": "sport"
    }
   </pre>
5) Create Comment:
   <pre>POST https://localhost/api/comments/</pre>
    <pre>
    {
        "article_id": *article_id*,
        "email": "s.alletti@gmail.com",
        "message": "test message"
    }
    </pre>
6) Get Article:
    <pre>GET https://localhost/api/articles/*article_id*</pre>

## About Me
* [Blog](https://stefanoalletti.wordpress.com/)
* [Linkedin](https://fr.linkedin.com/in/stefano-alletti)
* [Twitter](https://twitter.com/stefanoalletti)
* [Medium](https://medium.com/@stefanoalletti_40357)

## Resources
* [🇬🇧 https://matthiasnoback.nl/2018/06/doctrine-orm-and-ddd-aggregates/](https://matthiasnoback.nl/2018/06/doctrine-orm-and-ddd-aggregates/)
* [🇪🇸 https://github.com/CodelyTV/php-ddd-example](https://github.com/CodelyTV/php-ddd-example)
* [🇬🇧 https://latteandcode.medium.com/chapter-1-what-i-learned-from-ddd-basic-concepts-db887c397599](https://latteandcode.medium.com/chapter-1-what-i-learned-from-ddd-basic-concepts-db887c397599)
* [🇪🇸 https://latteandcode.medium.com/cap%C3%ADtulo-2-lo-que-aprend%C3%AD-de-ddd-value-objects-b5a28c2298cc](https://latteandcode.medium.com/cap%C3%ADtulo-2-lo-que-aprend%C3%AD-de-ddd-value-objects-b5a28c2298cc)
* [🇪🇸 https://latteandcode.medium.com/cap%C3%ADtulo-3-lo-que-aprend%C3%AD-de-ddd-entidades-a61cdb4b686](https://latteandcode.medium.com/cap%C3%ADtulo-3-lo-que-aprend%C3%AD-de-ddd-entidades-a61cdb4b686)
* [🇪🇸 https://latteandcode.medium.com/cap%C3%ADtulo-4-lo-que-aprend%C3%AD-de-ddd-servicios-a427413f4108](https://latteandcode.medium.com/cap%C3%ADtulo-4-lo-que-aprend%C3%AD-de-ddd-servicios-a427413f4108)
* [🇪🇸 https://latteandcode.medium.com/cap%C3%ADtulo-5-eventos-de-dominio-6743439a72bd](https://latteandcode.medium.com/cap%C3%ADtulo-5-eventos-de-dominio-6743439a72bd)
* [🇪🇸 https://latteandcode.medium.com/cap%C3%ADtulo-6-lo-que-aprend%C3%AD-de-ddd-m%C3%B3dulos-y-bounded-contexts-6a516fb52c12](https://latteandcode.medium.com/cap%C3%ADtulo-6-lo-que-aprend%C3%AD-de-ddd-m%C3%B3dulos-y-bounded-contexts-6a516fb52c12)
* [🇫🇷 https://blog.engineering.publicissapient.fr/2018/06/25/craft-les-patterns-tactiques-du-ddd/](https://blog.engineering.publicissapient.fr/2018/06/25/craft-les-patterns-tactiques-du-ddd/)
* [🇬🇧 https://www.mirkosertic.de/blog/2013/04/domain-driven-design-example/](https://www.mirkosertic.de/blog/2013/04/domain-driven-design-example/)
* [🇬🇧 https://www.culttt.com/2014/12/17/aggregates-domain-driven-design/](https://www.culttt.com/2014/12/17/aggregates-domain-driven-design/)
* [🇬🇧 https://speakerdeck.com/nealio82/the-absolute-beginners-guide-to-ddd-with-symfony](https://speakerdeck.com/nealio82/the-absolute-beginners-guide-to-ddd-with-symfony)
* [🇬🇧 http://wellfreire.github.io/en/symfony2-and-ddd-there-is-code-beyond-bundles/](http://wellfreire.github.io/en/symfony2-and-ddd-there-is-code-beyond-bundles/)

