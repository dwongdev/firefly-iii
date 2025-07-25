<?php

/**
 * ObjectGroupFormRequest.php
 * Copyright (c) 2019 james@firefly-iii.org
 *
 * This file is part of Firefly III (https://github.com/firefly-iii).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace FireflyIII\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use FireflyIII\Models\ObjectGroup;
use FireflyIII\Support\Request\ChecksLogin;
use FireflyIII\Support\Request\ConvertsDataTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

/**
 * Class ObjectGroupFormRequest.
 */
class ObjectGroupFormRequest extends FormRequest
{
    use ChecksLogin;
    use ConvertsDataTypes;

    /**
     * Returns the data required by the controller.
     */
    public function getObjectGroupData(): array
    {
        return [
            'title' => $this->convertString('title'),
        ];
    }

    /**
     * Rules for this request.
     */
    public function rules(): array
    {
        /** @var null|ObjectGroup $objectGroup */
        $objectGroup = $this->route()->parameter('objectGroup');
        $titleRule   = 'required|min:1|max:255|uniqueObjectGroup';

        if (null !== $objectGroup) {
            $titleRule = sprintf('required|min:1|max:255|uniqueObjectGroup:%d', $objectGroup->id);
        }

        return [
            'title' => $titleRule,
        ];
    }

    public function withValidator(Validator $validator): void
    {
        if ($validator->fails()) {
            Log::channel('audit')->error(sprintf('Validation errors in %s', self::class), $validator->errors()->toArray());
        }
    }
}
