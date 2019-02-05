# One field for all
Combine some field into json field ActiveRecord 

This table has only  field - `id`, `create`, `option`
The `option` is a json filed (for PostgreSQL is a jsonb)

```php
/**
 * Example class
 * @property integer $id
 * @property string $create
 * @property string $text
 * @property array $data
 */
class Example extends ActiveRecordJson
{
    protected $_json_attribute = 'option';
    protected $_json_catch = ['text', 'data', 'info'];

    public static function tableName()
    {
        return '{{example}}';
    }
    
    /**
    * For new record only
    */
    public function setAttributeDefault() {
        $this->text = 'Example text';
        $this->data = ['test'];
        $this->info = 2
    }
    
    public static function doSomeThing() {
        $thisData = self::findOne(1);
        echo $thisData->text;
        print_r($thisData->data);
    }
    
}
```
