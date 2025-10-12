# WAND


## About WAND

WAND is the official companion development toolkit for the Emberwhisk framework. It's designed to streamline your workflow by providing a simple and consistent way to install, configure, and manage your Emberwhisk projects.

By handling the tedious server management tasks, WAND allows you to focus on what matters: building your application's logic.

Key features of WAND include:

- **Simplified Installation:** Easily install Emberwhisk and its dependencies without manual configuration.

- **Live Development Server:** WAND provides a development server that automatically restarts whenever it detects changes to your project files. This makes for a seamless and highly productive development experience.

- **Environment Management:** Quickly set up and switch between different environments (e.g., development, staging) for your server.

- **Command-Line Interface:** Use a straightforward command-line interface to start, stop, and manage your Emberwhisk server.

WAND is the recommended way to get started with Emberwhisk and ensure a smooth, efficient, and enjoyable development experience.

Join us on our [Discord server.](https://discord.gg/gtwuf2A4Hq)

### How to Install WAND

**Important Note:** WAND is currently designed to be compatible only with **Linux**. If you are on another operating system, you will need to manually install and manage the [Emberwhisk Framework](https://github.com/drawfig/Emberwhisk/tree/master) by following its own installation instructions.

While WAND only requires a `PHP-cli` package to run, for its full feature set (which includes installing Emberwhisk), you will need the following dependencies:

* **PHP:** `cli`, `dev`, `PEAR/PECL`, `sqlite`, and `PDO` modules
* **OpenSwoole** (This will be installed automatically by WAND's `init` command if not present)
* The `npm` package

1.  **Clone the repository:**
    ```
    git clone https://github.com/drawfig/WAND.git
    ```

2.  **Run WAND:**
    ```
    cd WAND
    php wand
    ```

---

### Setting Up an Emberwhisk Server

Once WAND is running, you can use its commands to set up and manage your Emberwhisk server.

1.  **Initialize the server:**
    ```
    init
    ```
    > This command will install the necessary Emberwhisk dependencies and configure the server.
    >
    > **Note:** If your Linux distribution does not use `phpenmod` to enable PHP modules, you may have to manually enable the OpenSwoole extension. You can find detailed instructions [**here**](https://openswoole.com/docs/get-started/installation#enable-open-swoole-extension-in-php).

2.  **Generate your environment file:**
    ```
    gen-env
    ```
    > This command will walk you through creating an `.env` file for your selected environment. You will be prompted to add your server's configuration and, if you choose, a database connection for a MySQL or MariaDB instance.

3.  **Start the server:**
    ```
    start
    ```
    > This command will ask you which environment to start the server in. It will then launch a live server that automatically reloads whenever a file change is detected, making development easy and efficient.

## How to Contribute

WAND & Emberwhisk are open-source projects, and we welcome contributions from the community! If you'd like to get involved, here are a few ways you can help:
- **Report Bugs:** If you find a bug or an issue, please open an issue on the GitHub repository.
- **Submit Code:** Have an idea for a new feature or a bug fix? Feel free to fork the repository and submit a pull request.
- **Get in Touch:** For general questions, feature discussions, or to just chat about the project, you can contact me on Discord at `drawfig` or by joining [our server.](https://discord.gg/gtwuf2A4Hq).

Your contributions are greatly appreciated and help make Emberwhisk better for everyone.

## License
Emberwhisk is open-source software licensed under the Apache-2.0 License. A copy of the license is included in the root directory of this project.
