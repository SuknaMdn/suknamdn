
- 🛡 [Filament Shield](#plugins-used) for managing role access
- 👨🏻‍🦱 Profile page from [Filament Breezy](#plugins-used)
- 🌌 Managable media with [Filament Spatie Media](#plugins-used)
- 💌 Setting mail on the fly in Mail settings
- 🅻 Lang Generator

#### Latest update

##### Version: v1.15.xx

- Add *opcodesio/log-viewer*
- Add new plugins
- Bugs fix & Improvement
- Etc

#### Getting Started

Setup your env:

```bash
cd sokna
cp .env.example .env


Run migration & seeder:

```bash
php artisan migrate
php artisan db:seed
```

<p align="center">or</p>

```bash
php artisan migrate:fresh --seed
```

Generate key:

```bash
php artisan key:generate
```

Run :

```bash
npm run dev
OR
npm run build
```

```bash
php artisan serve
```

Now you can access with `/admin` path, using:

```bash
email: superadmin@sokna.sa
password: superadmin
```

#### Performance

*It's recommend to run below command as suggested in [Filament Documentation](https://filamentphp.com/docs/3.x/panels/installation#improving-filament-panel-performance) for improving panel perfomance.*

```bash
php artisan icons:cache
```

Please see this [Improving Filament panel performance](https://filamentphp.com/docs/3.x/panels/installation#improving-filament-panel-performance) documentation for further improvement

#### Language Generator

This project include lang generator.

```bash
php artisan superduper:lang-translate [from] [to]
```

Generator will look up files inside folder `[from]`. Get all variables inside the file; create a file and translate using `translate.googleapis.com`.

This is what the translation process looks like.

```bash
❯ php artisan superduper:lang-translate en fr es

 🔔 Translate to 'fr'
 3/3 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100% -- ✅

 🔔 Translate to 'es'
 1/3 [▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░░]  33% -- 🔄 Processing: page.php
```

##### Usage example

- Single output

```bash
php artisan superduper:lang-translate en ar
```

- Multiple output

```bash
php artisan superduper:lang-translate en es ar fr pt-PT pt-BR zh-CN zh-TW
```

###### If you are using json translation

```bash
php artisan superduper:lang-translate en ar --json
```


#### Plugins

These are [Filament Plugins](https://filamentphp.com/plugins) use for this project.

| **Plugin**                                                                                          | **Author**                                          |
| :-------------------------------------------------------------------------------------------------- | :-------------------------------------------------- |
| [Filament Spatie Media Library](https://github.com/filamentphp/spatie-laravel-media-library-plugin) | [Filament Official](https://github.com/filamentphp)   |
| [Filament Spatie Settings](https://github.com/filamentphp/spatie-laravel-settings-plugin)           | [Filament Official](https://github.com/filamentphp)   |
| [Filament Spatie Tags](https://github.com/filamentphp/spatie-laravel-tags-plugin)                   | [Filament Official](https://github.com/filamentphp)   |
| [Shield](https://github.com/bezhanSalleh/filament-shield)                                           | [bezhansalleh](https://github.com/bezhansalleh)     |
| [Exceptions](https://github.com/bezhansalleh/filament-exceptions)                                   | [bezhansalleh](https://github.com/bezhansalleh)     |
| [Breezy](https://github.com/jeffgreco13/filament-breezy)                                            | [jeffgreco13](https://github.com/jeffgreco13)       |
| [Logger](https://github.com/z3d0x/filament-logger)                                                  | [z3d0x](https://github.com/z3d0x)                   |
| [Ace Code Editor](https://github.com/riodwanto/filament-ace-editor)                                 | [riodwanto](https://github.com/riodwanto)           |
| [Filament Record Navigation Plugin](https://github.com/josespinal/filament-record-navigation)       | [josespinal](https://github.com/josespinal)         |
| [Filament media manager](https://github.com/tomatophp/filament-media-manager)                       | [tomatophp](https://github.com/tomatophp)           |
| [Filament Menu Builder](https://github.com/datlechin/filament-menu-builder)                         | [datlechin](https://github.com/datlechin)           |
| [Map Picker](https://github.com/dotswan/filament-map-picker)                                             | [Dotswan](https://github.com/dotswan)               |
| [Overlook](https://github.com/awcodes/filament-overlook)                                             | [AWCodes](https://github.com/awcodes)               |
| [Excel](https://github.com/muath-alsowadi/filament-excel)                                             | [Muath Alsowadi](https://github.com/muath-alsowadi)               |
| [Superduper Starter](https://github.com/riodwanto/superduper-filament-starter-kit)                                             | [Riodwanto](https://github.com/riodwanto)               |
| [Settings Hub](https://github.com/tomatophp/filament-settings-hub)                                             | [TomatoPHP](https://github.com/tomatophp)               |
| [Rupadana Api Service](https://github.com/rupadana/filament-api-service)                                             | [Rupadana](https://github.com/rupadana)               |


### opt doc
- https://medium.com/@sadiqsalau/sending-and-verifying-otp-codes-in-laravel-the-easy-way-b946946bf467

### Settings plugin
- https://filamentphp.com/plugins/3x1io-tomato-settings-hub

### Blade Icons
- https://blade-ui-kit.com/blade-icons/tabler-currency-riyal

### Rupadana Api Service
- https://filamentphp.com/plugins/rupadana-api-service#filtering--allowed-field

#### Generate Shield

```bash
php artisan shield:generate
php artisan shield:super-admin
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan filament:optimize
php artisan optimize
**important**
php artisan icons:cache

**from artisan menu**
php artisan storage:link
```
