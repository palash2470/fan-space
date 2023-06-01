<?php
$user_cats = \App\User_category::orderBy('display_order')->get();
$user_orientation = \App\Sexual_orientation::get();
$st = $meta_data['search_params']['st'] ?? [];
$lst = $meta_data['search_params']['lst'] ?? [];
$uc = $meta_data['search_params']['uc'] ?? [];
$uo = $meta_data['search_params']['uo'] ?? [];
$age = $meta_data['search_params']['age'] ?? [];
?>

<div class="modal fade modelModal search_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content relative">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12">

                            <div class="modelModalBox w-100">
                                <div class="form-group from-input-wrap">
                                    <h5>Search</h5>
                                    <input type="text" name="s" id="" class="input-3" placeholder="Search model" value="{{ $meta_data['search_params']['s'] ?? '' }}" />
                                </div>
                                <h5>Status</h5>
                                <ul>
                                    <li class="checkbox checkbox-info">
                                        <input id="cb1" class="styled" type="checkbox" name="lst[]" value="1" {{ in_array('1', $lst) ? 'checked="checked"' : '' }} />
                                        <label for="cb1">Online</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="cb2" class="styled" type="checkbox" name="lst[]" value="0" {{ in_array('0', $lst) ? 'checked="checked"' : '' }} />
                                        <label for="cb2">Offline</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="modelModalBox w-100">
                                <h5>Category</h5>
                                <ul>
                                    <?php
                                    foreach ($user_cats as $key => $value) {
                                        echo '<li class="checkbox checkbox-info">
                                            <input id="uc' . $value->id . '" class="styled" type="checkbox" name="uc[]" value="' . $value->id . '" ' . (in_array($value->id, $uc) ? 'checked="checked"' : '') . ' />
                                            <label for="uc' . $value->id . '">' . $value->title . '</label>
                                        </li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="modelModalBox w-100">
                                <h5>Sexual Orientation</h5>
                                <ul>
                                    <?php
                                    foreach ($user_orientation as $key => $value) {
                                        echo '<li class="checkbox checkbox-info">
                                            <input id="uo' . $value->id . '" class="styled" type="checkbox" name="uo[]" value="' . $value->id . '" ' . (in_array($value->id, $uo) ? 'checked="checked"' : '') . ' />
                                            <label for="uo' . $value->id . '">' . $value->title . '</label>
                                        </li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="modelModalBox w-100">
                                <h5>Age Group</h5>
                                <ul>
                                    <li class="checkbox checkbox-info">
                                        <input id="cb6" class="styled" type="checkbox" name="age[]" value="18_24" {{ in_array('18_24', $age) ? 'checked="checked"' : '' }} />
                                        <label for="cb6">18 to 24 Years</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="cb7" class="styled" type="checkbox" name="age[]" value="25_34" {{ in_array('25_34', $age) ? 'checked="checked"' : '' }} />
                                        <label for="cb7">25 to 34 Years</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="cb8" class="styled" type="checkbox" name="age[]" value="35_44" {{ in_array('35_44', $age) ? 'checked="checked"' : '' }} />
                                        <label for="cb8">35 to 44 Years</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="cb9" class="styled" type="checkbox" name="age[]" value="45_54" {{ in_array('45_54', $age) ? 'checked="checked"' : '' }} />
                                        <label for="cb9">45 to 54 Years</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="cb10" class="styled" type="checkbox" name="age[]" value="55_64" {{ in_array('55_64', $age) ? 'checked="checked"' : '' }} />
                                        <label for="cb10">55 to 64 Years</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="cb11" class="styled" type="checkbox" name="age[]" value="65_100" {{ in_array('65_100', $age) ? 'checked="checked"' : '' }} />
                                        <label for="cb11">65+ Years</label>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 text-right">
                            <button type="button" class="commonBtn adv_search_submit">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
