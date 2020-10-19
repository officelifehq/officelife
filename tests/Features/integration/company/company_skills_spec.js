describe('Company - Skills', function () {
  it('should let you see all the skills in the company on the company page as an administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    // we need to create another employee to test that we can lock an employee and not see him in the list of employees
    // for a given skill
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);
    cy.get('[name=\'Michael Scott\']').invoke('attr', 'data-invitation-link').then((link) => {
      cy.logout();
      cy.visit('/invite/employee/' + link);
      cy.get('[data-cy=accept-create-account]').click();
      cy.get('input[name=password]').type('admin1012');
      cy.get('[data-cy=create-cta]').click();
      cy.get('body').should('contain', 'Dunder Mifflin');
      cy.logout();
    });

    // log back the admin
    cy.get('input[name=email]').type('admin@admin.com');
    cy.get('input[name=password]').type('admin');
    cy.get('button[type=submit]').click();
    cy.wait(1000);

    // create first skill for admin
    cy.visit('/1/employees/1');
    cy.get('[data-cy=manage-skill-button]').click();
    cy.get('[data-cy=search-skill]').type('php');
    cy.get('[data-cy=submit-create-skill]').click();

    // add same skill for michael
    cy.visit('/1/employees/2');
    cy.get('[data-cy=manage-skill-button]').click();
    cy.get('[data-cy=search-skill]').type('php');
    cy.get('[data-cy=skill-name-1]').click();
    cy.get('[data-cy=cancel-add-skill]').click();

    // see the list of skills
    cy.visit('/1/company/');
    cy.get('[data-cy=company-skills]').contains('1');
    cy.get('[data-cy=company-skills]').click();
    cy.get('[data-cy=skill-item-1]').contains('php');
    cy.get('[data-cy=skill-item-1]').contains('2');
    cy.get('[data-cy=skill-item-1]').click();

    // see a specific skill
    cy.get('[data-cy=skill-name]').contains('php');
    cy.get('[data-cy=list-of-employees]').contains('admin@admin.com');
    cy.get('[data-cy=list-of-employees]').contains('Michael Scott');

    // lock Michael and see him disappear from the list of employees for the skill
    cy.visit('/1/account/employees');
    cy.get('[data-cy=lock-account-2').click();
    cy.get('[data-cy=submit-lock-employee-button').click();
    cy.visit('/1/company/skills');
    cy.get('[data-cy=skill-item-1]').contains('php');
    cy.get('[data-cy=skill-item-1]').contains('1');
    cy.get('[data-cy=skill-item-1]').click();
    cy.get('[data-cy=list-of-employees]').should('not.contain', 'Michael Scott');

    // rename a skill
    cy.visit('/1/company/skills/1');
    cy.get('[data-cy=edit-skill]').click();
    cy.get('[data-cy=rename-cancel-button]').click();
    cy.get('[data-cy=edit-skill]').click();
    cy.get('[data-cy=edit-name-input]').clear();
    cy.get('[data-cy=edit-name-input]').type('java');
    cy.get('[data-cy=rename-cta-button]').click();
    cy.get('[data-cy=skill-name]').contains('java');
    cy.hasAuditLog('Updated the skill’s name from php to java', '/1/company/skills/1');

    // delete a skill
    cy.get('[data-cy=delete-skill]').click();
    cy.get('[data-cy=delete-cancel-button]').click();
    cy.get('[data-cy=delete-skill]').click();
    cy.get('[data-cy=delete-confirm-button]').click();
    cy.url().should('include', '/company/skills');
    cy.hasAuditLog('Deleted the skill called java', '/1/company/skills/1');
  });

  it('should let you see all the skills in the company on the company page as an HR', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200);
    });

    // create first skill
    cy.visit('/1/employees/1');
    cy.get('[data-cy=manage-skill-button]').click();
    cy.get('[data-cy=search-skill]').type('php');
    cy.get('[data-cy=submit-create-skill]').click();

    // see the list of skills
    cy.visit('/1/company/');
    cy.get('[data-cy=company-skills]').contains('1');
    cy.get('[data-cy=company-skills]').click();
    cy.get('[data-cy=skill-item-1]').contains('php');
    cy.get('[data-cy=skill-item-1]').contains('1');
    cy.get('[data-cy=skill-item-1]').click();

    // see a specific skill
    cy.get('[data-cy=skill-name]').contains('php');
    cy.get('[data-cy=list-of-employees]').contains('admin@admin.com');
  });

  it('should let you see all skills on the company page a normal user', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 300);
    });

    // create first skill
    cy.visit('/1/employees/1');
    cy.get('[data-cy=manage-skill-button]').click();
    cy.get('[data-cy=search-skill]').type('php');
    cy.get('[data-cy=submit-create-skill]').click();

    // see the list of skills
    cy.visit('/1/company/');
    cy.get('[data-cy=company-skills]').contains('1');
    cy.get('[data-cy=company-skills]').click();
    cy.get('[data-cy=skill-item-1]').contains('php');
    cy.get('[data-cy=skill-item-1]').contains('1');
    cy.get('[data-cy=skill-item-1]').click();

    // see a specific skill
    cy.get('[data-cy=skill-name]').contains('php');
    cy.get('[data-cy=list-of-employees]').contains('admin@admin.com');
  });
});
