<?php
//importando arquivo functions.php de dentro da pasta funcs
include 'funcs/functions.php';

//criando conexão por meio do PDO
$pdo = pdo_connect_mysql();

$msg = '';

if (isset($_GET['id'])) {
    // selecionando contato do banco de dados a partir do ID
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    // se o contato pelo ID não existir envia um alerta
    if (!$contact) {
        exit('Não existe contato com esse ID');
    }
    // se for confirmado a exclusão então o contato é deletado
    // o valor de confirmação ou não é enviado via metodo GET a partir do formulário
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'Contato deletado! </p></p> <a href="read.php">Voltar a lista de contatos.</a>';

        } else {
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('Sem id especificado!');
}
?>

<!-- definindo template para Deletar Contato -->
<?=template_header('Delete')?>

<div class="content delete">
	<h2>Deletar contato nº #<?=$contact['id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Você realmente deseja excluir o contato nº #<?=$contact['id']?>?</p>
    <div class="yesno">
        <a href="delete.php?id=<?=$contact['id']?>&confirm=yes">Yes</a>
        <a href="delete.php?id=<?=$contact['id']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>