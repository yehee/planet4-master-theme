<?php 


declare(strict_types=1);

namespace P4\MasterTheme;

use Timber;

/**
 * Class User extends Timber\User.
 *
 * Ref: https://timber.github.io/docs/reference/timber-user/
 */
class User extends Timber\User
{

	/**
	 * Is a fake user flag
	 *
	 * @var bool $is_fake
	 */
	public $is_fake = false;

	/**
	 * User constructor.
	 *
	 * @param object|int|bool $uid The User id.
	 * @param string          $author_override The author override display name.
	 */
	public function __construct($uid = false, string $author_override = '')
	{
		if (! $author_override) {
			parent::__construct($uid);
		} else {
			$this->display_name = $author_override;
			$this->is_fake = true;
		}
	}

	/**
	 * The User profile page url.
	 */
	public function link(): string
	{
		return $this->is_fake
			? '#'
			: parent::link();
	}

	/**
	 * The relative path of the User profile page.
	 */
	public function path(): string
	{
		return $this->is_fake
			? '#'
			: parent::path();
	}

	/**
	 * Author display name.
	 */
	public function name(): ?string
	{
		return $this->is_fake
			? (string) $this->display_name
			: parent::name();
	}

	/**
	 * Stringifies the User object.
	 */
	public function __toString(): ?string
	{
		return $this->is_fake
			? $this->name()
			: parent::__toString();
	}
}
