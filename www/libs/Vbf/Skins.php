<?php

class Vbf_Skins
{
    private $baseDirectory;
    private $baseUrl;
    private $defaultSkin;
    private $currentSkin;

    public function __construct($baseDirectory, $baseUrl, $defaultSkin)
    {
        $this->baseDirectory = $baseDirectory;
        $this->baseUrl = $baseUrl;
        $this->defaultSkin = $defaultSkin;

        $this->SetCurrentSkin($defaultSkin);
    }

    /**
     * Change the current skin to a new one.
     *
     * @param string $skinName name of the skin that will now be used.
     */
    public function setCurrentSkin($skinName)
    {
        $this->currentSkin = $skinName;
    }

    /**
     * Check if the current skin is the defaut one
     *
     * @return true if the current skin is the default one, false otherwise.
     */
    public function currentSkinIsDefault()
    {
        return ($this->currentSkin == $this->defaultSkin);
    }

    /**
     * Get the absolute file path where a template could be found.
     *
     * @param string $templateName Name of the template
     * @return a string containing the absolute path of the template file.
     */
    public function getTemplatePath($templateName)
    {
        return $this->getFilePath('templates/' . $templateName . '.tpl.php');
    }

    /**
     * Get the absolute file path of a file inside a skin if it exists
     *
     * @param string $skinName Name of the skin
     * @param string $localFileName Path of the file relative to the skin directory.
     * @return A string containing the absolute path of the file if found in the specified skin, NULL otherwise.
     */
    private function getSkinFilePathIfExists($skinName, $localFileName)
    {
        $filePath = $this->getSkinFilePath($skinName, $localFileName);

        if (file_exists($filePath)) return $filePath;
        return NULL;
    }

    private function getSkinFilePath($skinName, $localFileName)
    {
        return $this->baseDirectory . $skinName . '/' . $localFileName;
    }

    private function getSkinFileUrl($skinName, $localFileName)
    {
        return $this->baseUrl . $skinName . '/' . $localFileName;
    }

    /**
     * Search the currentSkin and defaultSkin directories to find in witch one the
     * specified file is.
     *
     * @param string $localFileName Path of the file relative to the skin directory.
     * @return A string containing the name of the skin where the file was found if it is found somewhere, NULL otherwise.
     */
    private function getSkinForFile($localFileName)
    {
        $currentFileName = $this->GetSkinFilePathIfExists($this->currentSkin, $localFileName);
        if ($currentFileName != NULL) return $this->currentSkin;

        if (!$this->CurrentSkinIsDefault()) {
            $defaultFileName = $this->GetSkinFilePathIfExists($this->defaultSkin, $localFileName);
            if ($defaultFileName != NULL) return $this->defaultSkin;
        }

        return NULL;
    }

    /**
     * Get the URL (without the "protocol://domain.tld" part) where the specified file
     * could be found.
     *
     * This function should be used when referencing images, css files or other resources
     * in the HTML output of a skin.
     *
     * @param string $localFileName Path of the file relative to the skin directory.
     * @throws Exception
     * @return The URL where the specified file could be found. An exception is thrown otherwise.
     */
    public function getFileURL($localFileName)
    {
        $skin = $this->GetSkinForFile($localFileName);

        if ($skin != NULL)
            return $this->GetSkinFileUrl($skin, $localFileName);
        else
            throw new Exception("Unable to find the file '$localFileName' in the current or default skin folders.");
    }

    /**
     * Get the absolute file path where the specified file could be found.
     *
     * @param string $localFileName Path of the file relative to the skin directory.
     * @throws Exception
     * @return  The absolute path where the specified file could be found. An exception is thrown otherwise.
     */
    public function getFilePath($localFileName)
    {
        $skin = $this->GetSkinForFile($localFileName);

        if ($skin != NULL)
            return $this->GetSkinFilePath($skin, $localFileName);
        else
            throw new Exception("Unable to find the file '$localFileName' in the current or default skin folders.");
    }

    /**
     * Parse a template and return the result as a string.
     *
     * @param string $__templateName Name of the template, relative to the template directory without the extension.
     * @param string $__templateVariables An associative array of "name => values" that will be converted to local variables in the template.
     * @return A string containing the result of the template parsing.
     */
    public function fetchTemplate($__templateName, $__templateVariables = NULL)
    {
        if ($__templateVariables != NULL) {
            extract($__templateVariables, EXTR_SKIP);
        }

        ob_start();
        require($this->GetTemplatePath($__templateName));
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }

    /**
     * Parse a template and display the result.
     *
     * @param string $__templateName Name of the template, relative to the template directory without the extension.
     * @param string $__templateVariables An associative array of "name => values" that will be converted to local variables in the template.
     */
    public function outputTemplate($__templateName, $__templateVariables = NULL)
    {
        if ($__templateVariables !== NULL) {
            extract($__templateVariables, EXTR_SKIP);
        }

        require($this->GetTemplatePath($__templateName));
    }
}

?>
