<h1>View Teste</h1>
<p>Com dados dinâmicos: <?= $person->name ?></p>
<?= \Nano\Partial::get( __DIR__ . '/../views/_hi.php', [ 'person' => $person ] ) ?>