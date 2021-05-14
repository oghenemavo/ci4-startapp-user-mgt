<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
        
    <?= var_dump(session()->getFlashdata()); ?>

    <?php
        // Open form
        $attributes = ['id' => 'update-profile'];
        echo form_open(route_to('user_edit_profile'), $attributes);
    ?>
        <div class="form-group">
            <label for="firstname" class="sr-only sr-only-focusable">FIRST NAME</label>
            <input type="text" class="form-control" name="firstname" id="firstname" value="<?= $user->first_name ?>" placeholder="FIRST NAME">
            <?= $validation->showError('firstname'); ?>
        </div>
        <div class="form-group">
            <label for="lastname" class="sr-only sr-only-focusable">LAST NAME</label>
            <input type="text" class="form-control" name="lastname" id="lastname" value="<?= $user->last_name ?>" placeholder="LAST NAME">
            <?= $validation->showError('lastname'); ?>
        </div>

        <div class="form-group">
            <label for="phone" class="sr-only sr-only-focusable">PHONE NUMBER</label>
            <input type="text" class="form-control" name="phone" id="phone" value="<?= $user->phone_number ?>" placeholder="PHONE NUMBER">
            <?= $validation->showError('phone'); ?>
        </div>

        <div class="custom-control custom-radio">
            <input type="radio" id="male" name="gender" class="custom-control-input" value="Male" <?= $user->user_gender == 'Male' ? 'checked' : '' ?> >
            <label class="custom-control-label" for="male">Male</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="female" name="gender" class="custom-control-input" value="Female" <?= $user->user_gender == 'Female' ? 'checked' : '' ?>>
            <label class="custom-control-label" for="female">Female</label>
            <?= $validation->showError('gender'); ?>
        </div>

        <!-- <div class="form-group">
            <label for="date_ob" class="sr-only sr-only-focusable">DATE OF BIRTH (optional)</label>
            <input type="date" class="form-control" name="date_ob" id="date_ob" value="<?//= $user->user_profile_dob ?>">
            <?//= $validation->showError('date_ob'); ?>
        </div> -->

        <div class="btn-demo my-3">
            <button type="submit" class="btn btn-indigo active mg-b-10">Update Profile</button>
        </div>
    </form>
    
<?= $this->endSection() ?>