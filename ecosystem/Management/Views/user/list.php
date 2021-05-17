<?php use CodeIgniter\I18n\Time; ?>
<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>

<table id="users-list" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>DOR</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user->last_name . ' ' . $user->first_name ?></td>
        <td>
            <a href="<?= base_url(route_to('show_user', $user->id )) ?>">
                <?= $user->user_email ?>
            </a>
        </td>
        <td><?= Time::parse($user->created_at)->toLocalizedString('MMM d, yyyy') ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>DOR</th>
        </tr>
    </tfoot>
</table>

<?= $this->endSection() ?>