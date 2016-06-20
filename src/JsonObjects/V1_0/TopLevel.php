<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use LaLu\JDR\Models\V1_0\ResourceInterface;

class User extends Model implements Authenticatable, ResourceInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'password_hash',
        'avatar',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        //
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     */
    public function setRememberToken($value)
    {
        //
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        //
    }

    public function getResourceId()
    {
        return $this->getKey();
    }

    public function getResourceType()
    {
        return 'user';
    }

    public function getResourceAttributes()
    {
        return [
            'username' => $this->username,
            'name' => $this->name,
            'avatar' => $this->getAvatarUrl(),
            'description' => $this->description,
            'createdAt' => (string)$this->created_at,
            'updatedAt' => (string)$this->updated_at,
        ];
    }

    public function getResourceLinks()
    {
        return [
            'self' => route('v1.users.show', ['id' => $this->getKey()]),
        ];
    }

    public static function createUser($username, $password, $name, $avatar = null, $description = null)
    {
        $user = new static();
        $user->name = $name;
        $user->username = $username;
        $user->password_hash = bcrypt($password);
        if (!empty($avatar)) {
            $user->avatar = $avatar;
        }
        if (!empty($description)) {
            $user->description = $description;
        }
        $user->saveOrFail();

        return $user;
    }

    public function getSearchable()
    {
        return [
            'name',
            'username',
            'description',
        ];
    }

    public static function getImagePath($dir = null)
    {
        return public_path("users/images/$dir");
    }

    public function getAvatarUrl()
    {
        return empty($this->avatar) ? null : url(str_replace(base_path(), '', $this->avatar));
    }

    public function delete()
    {
        $result = parent::delete();
        if ($result === true && !empty($this->avatar) && file_exists($this->avatar)) {
            unlink($this->avatar);
        }

        return $result;
    }

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }

    /**
     * Get relationship models
     *
     * @return ResourceInterface[]
     */
    public function getRelationships()
    {
        return [
            'timelines' => [
                'data' => $this->timelines()->orderBy('year', 'asc')->orderBy('month', 'asc')->get()
            ],
        ];
    }
}
