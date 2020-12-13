describe('Adminland - Team management', function () {
  it('should create a team', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createTeam('product');

    cy.contains('product');

    cy.hasAuditLog('Created the team called product', '/1/account/teams');
    cy.hasTeamLog('Created the team', '/1/account/teams');
  });

  it('should let rename and delete a team as an administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createTeam('product');

    cy.get('[data-cy=team-rename-link-1]').click();
    cy.get('[data-cy=list-rename-input-name-1]').clear();
    cy.get('[data-cy=list-rename-input-name-1]').type('sales');
    cy.get('[data-cy=list-rename-cta-button-1]').click();
    cy.get('[data-cy=list-team-1]').contains('sales');

    cy.hasAuditLog('Changed the name of the team from product to sales', '/1/account/teams');
    cy.hasTeamLog('Changed the name from product to sales', '/1/account/teams');

    cy.get('[data-cy=team-destroy-link-1]').click();
    cy.get('[data-cy=list-destroy-cancel-button-1]').click();
    cy.get('[data-cy=team-destroy-link-1]').click();
    cy.get('[data-cy=list-destroy-cta-button-1]').click();
    cy.get('[data-cy=list-team-1]').should('not.exist');

    cy.hasAuditLog('Deleted the team called sales', '/1/account/teams');
  });

  it('should let rename and delete a team as an HR', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200);
    });

    cy.createTeam('product');

    cy.get('[data-cy=team-rename-link-1]').click();
    cy.get('[data-cy=list-rename-input-name-1]').clear();
    cy.get('[data-cy=list-rename-input-name-1]').type('sales');
    cy.get('[data-cy=list-rename-cta-button-1]').click();
    cy.get('[data-cy=list-team-1]').contains('sales');
    cy.hasTeamLog('Changed the name from product to sales', '/1/account/teams');

    cy.get('[data-cy=team-destroy-link-1]').click();
    cy.get('[data-cy=list-destroy-cancel-button-1]').click();
    cy.get('[data-cy=team-destroy-link-1]').click();
    cy.get('[data-cy=list-destroy-cta-button-1]').click();
    cy.get('[data-cy=list-team-1]').should('not.exist');
  });
});
