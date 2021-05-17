<?php use CodeIgniter\I18n\Time; ?>
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

    <dt>Privilege</dt>
    <dt><?= $user->role ?></dt>

</dl>

<ul>
    <?php if ($userlib->has_privilege('edit_user')): ?>
    <li><a href="<?= base_url(route_to('edit_user', $user->id)) ?>">Edit</a></li>
    <?php endif; ?>
    <?php if ($userlib->has_privilege('delete_user')): ?>
        <?php if ($user->id != $userlib->get_user()->id): ?>
            <!-- create a modal and add a form to it-->
            <!-- use chrome to view dialog-->
            <?php if ($user->role_name != 'Super Admin'): ?>
                <li>
                    <?php
                        //open form
                        $attributes = ['id' => 'update-profile'];
                        echo form_open(base_url('admin/delete/user/profile/'. $user->id), $attributes);
                    ?>
                    <button type="submit">Delete</button>

                    </form>
                    <!--<dialog>-->
                    <!--    <h4>Delete <?//= $user->user_first_name . ' ' . $user->user_last_name ?>--</h4>-->
                    <!--    <p>are you sure you want to delete this user?</p>-->
                    <!--    <form>-->
                    <!--        <button type="submit">Delete</button>-->
                    <!--    </form>-->
                    <!--    <button type="button" id="exit">Cancel</button>-->
                    <!--</dialog>-->
                    <!--<button type="button" id="exit">Delete user</button>-->
                    <!-- use chrome to view dialog-->
                </li>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</ul>

<script>
    // use chrome to view the dialog
    (() => {
        let dialog = document.getElementById('window');
        document.getElementById('show').addEventListener('click', () => dialog.show());
        document.getElementById('exit').addEventListener('click', () => dialog.close());
    })();
</script>

<?= $this->endSection() ?>