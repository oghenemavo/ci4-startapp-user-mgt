<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>

<h4>Edit <?= $role_info->role ?></h4>

<?php
    // Open form
    $attributes = ['id' => 'role-permission'];
    echo form_open(route_to('edit_rp', $role_perm->id), $attributes);
    $is_checked = '';
?>

<?php
    if (!empty($role_permissions)) {
        foreach ($role_permissions as $rp) {
            $set_permissions[] = $rp->permission_id;
            $active_array[$rp->permission_id] = $rp->is_active;
        }
    }
?>

<?php foreach($permission_group as $group): ?>
    <h4><?= $group->group_name ?></h4>

    <?php foreach($permissions as $permission): ?>
        
        <?php if($permission->perm_group_id == $group->id): ?>
            <?php
                if (!empty($role_permissions)) {
                    if (in_array($permission->id, $set_permissions)) {
                        $is_checked = $active_array[$permission->id] == '1' ? 'checked' : ''; // if role permission is checked
                        // $is_active = $rp->is_active; // if role permission is active
                    }
                }
            ?>

            <label for="perm-<?= $permission->id ?>"><?= $permission->permission_slug ?></label>
            <input type="checkbox" 
                name="permission[]" id="perm-<?= $permission->id ?>" 
                value="<?= $permission->id ?>"
                <?= $is_checked ?>
            >
        <?php endif; ?>
        
    <?php endforeach; ?>
<?php endforeach; ?>

    <br>
    <button type="submit">Edit Role Permission</button>

</form>

<?= $this->endSection() ?>
