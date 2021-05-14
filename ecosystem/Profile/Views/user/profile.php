<?php
use CodeIgniter\I18n\Time;
?>
<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
    <dl>
        <dt>First Name</dt>
        <dt><?= $user->first_name ?></dt>

        <dt>Last Name</dt>
        <dt><?= $user->last_name ?></dt>

        <dt>Email</dt>
        <dt><?= $user->user_email ?></dt>

        <dt>Gender</dt>
        <dt><?= $user->user_gender ?? 'not specified' ?></dt>

        <dt>DOB</dt>
        <dt>20 Oct, 2016</dt>

        <dt>DOR</dt>
        <dt><?= Time::parse($user->created_at)->toLocalizedString('MMM d, yyyy') ?></dt>

        <dt>Active</dt>
        <dd><?php echo $user->is_active ? '&#10004;' : '&#10008;'; ?></dd>

        <!-- <dt>Privilege</dt>
        <dt><?//= $user->role_name ?></dt> -->

    </dl>

    <ul>
        <li><a href="profile/edit">Edit</a></li>
    </ul>

<?= $this->endSection() ?>